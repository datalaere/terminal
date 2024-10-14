<?php

namespace App\Controllers;

use App\Providers\Controller;

class DebugController extends Controller
{

    public function dump()
    {
        $data = request()->get('data');

        $data = trim(strtoupper($data));
        $max_words = rand(5, 17);
        $max_attempts = 4;
    
        if (!isset($_SESSION['debug_pass'])) {
    
            $_SESSION['word'] = rand(2, 13);
            $_SESSION['debug_pass'] = wordlist(config('views') . '/lists/wordlist.txt', $_SESSION['word'] , 1)[0];
        } 
        
        $word_length = $_SESSION['word']; 
        $admin_pass = $_SESSION['debug_pass'];
    
        // Initialize attempts if not already set
        if (!isset($_SESSION['debug_attempts'])) {
            $_SESSION['debug_attempts'] = $max_attempts;
        }
    
        if (!isset($_SESSION['dump'])) {
            $word_list = wordlist(config('views') . '/lists/wordlist.txt', $word_length, $max_words);
            $data = array_merge([$admin_pass], $word_list);
    
            // Number of rows and columns in the memory dump
            $rows = 17;
            $columns = 3;
    
            // Generate the memory dump
            $memoryDump = mem_dump($rows, $columns, $data, $word_length);
    
            // Format and output the memory dump with memory paths
            if (!isset($_SESSION['debug'])) {
                view('/robco/debug.txt');
            }
    
            echo "{$_SESSION['debug_attempts']} ATTEMPT(S) LEFT: # # # # \n \n";
    
            $_SESSION['dump'] = format_dump($memoryDump);
            echo $_SESSION['dump'];
            exit;
        } else {
    
            if ($data != $admin_pass) {
                $match = count_match_chars($data, $admin_pass);
                $_SESSION['dump'] = str_replace($data, replaceWithDots($data), $_SESSION['dump']);
    
                if(preg_match('/\([^()]*\)|\{[^{}]*\}|\[[^\[\]]*\]|<[^<>]*>/', $data)) {
                    echo "Dud Removed.\n";
                    echo "Tries Reset.\n";
    
                    if($_SESSION['debug_attempts'] < 4) {
                        $_SESSION['debug_attempts']++;
                    }
                }
    
                if(preg_match('/^[a-zA-Z]+$/', $data)) {
                    $_SESSION['debug_attempts']--;
                }
    
                echo "Entry denied.\n";
                echo "{$match}/{$word_length} correct.\n";
                echo "Likeness={$match}.\n \n";
    
                if ($_SESSION['debug_attempts'] === 1) {
                    echo "!!! WARNING: LOCKOUT IMMINENT !!!\n\n";
                }
    
               $attemps_left = str_char_repeat($_SESSION['debug_attempts']);
    
                echo "{$_SESSION['debug_attempts']} ATTEMPT(S) LEFT: {$attemps_left} \n \n";
    
                if ($_SESSION['debug_attempts'] <= 0) {
                    $_SESSION['user_blocked'] = true;
                    echo "ERROR: TERMINAL LOCKED.\nPlease contact an administrator\n";
                    exit;
                }
    
                echo $_SESSION['dump'];
                exit;
            } else {
                // Reset login attempts on successful login
                unset($_SESSION['debug_attempts']);
                unset($_SESSION['user_blocked']);
    
                /*
                // Store the new user credentials
                $username = $_SESSION['USER']['ID'];
                $server['accounts'][$username] = strtolower($admin_pass);
                 // Save the updated user data to the file
                file_put_contents(APP. "server/{$server_id}.json", json_encode($server));
    
                // Add one to the XP field
                if (isset($_SESSION['USER'])) {
                    $user_id = $_SESSION['USER']['ID'];
                    $_SESSION['USER']['XP'] += 50;
                    file_put_contents(APP. "user/{$user_id}.json", json_encode($_SESSION['USER']));
                    echo "+0050 XP \n";
                }
                */
    
                echo "EXCACT MATCH!\n";
                echo "USERNAME: " . strtoupper(auth()->user()->username) . "\n";
                return "PASSWORD: {$admin_pass}\n";
            }
    
        }
    }

    public function set($request, $response) 
    {
        $data = request()->get('data');

        if(empty($data)) {
            echo 'ERROR: Missing Parameters!';
            exit;
        }
    
        $command = strtoupper($data);
    
        if(strpos('TERMINAL/INQUIRE', $command) !== false) {
            echo 'RIT-V300'. "\n";
            exit;
        }
    
        if(strpos('FILE/PROTECTION=OWNER:RWED ACCOUNTS.F', $command) !== false) {
            session()->set('root', true);
            return "Root (5A8) \n";
        }
    
        if(strpos('HALT', $command) !== false) {
            $this->user->logout();
            
            return 'SHUTTING DOWN...';
        }
    
        if(strpos('HALT RESTART', $command) !== false) {
            echo 'RESTARTING...';
            return view('robco/boot.txt') . "\n";
        }
    
        if(strpos('HALT RESTART/MAINT', $command) !== false) {
            session()->set('maint', true);
            return view('robco/maint.txt') . "\n";
        }
       
    }

    public function run() { 

        $data = request()->get('data');

        if(empty($data)) {
            return 'ERROR: Missing Parameters!';
        }
    
        $command = strtoupper($data);
    
        if(!isset($_SESSION['root'])) {
            return 'ERROR: Root Access Required!';
        }
        
        if(!isset($_SESSION['maint'])) {
            return 'ERROR: Maintenance Mode Required!';
        }
    
        if(strpos('LIST/ACCOUNTS.F', $command) !== false) {
            return listAccounts();
        }
    
        if(strpos('DEBUG/ACCOUNTS.F', $command) !== false) {
            session()->set('debug', true);
            echo view('robco/attempts.txt') . "\n";
            return dump($data);
        }
    }

}