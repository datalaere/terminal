<?php

namespace App\Controllers;

use App\Models\User;
use App\Providers\Controller;

class UserController extends Controller
{

    private $user_name = 'user_name';
    private $password = 'password';
    private $firstname = 'firstname';
    private $lastname = 'lastname';
    private $created = 'created_at';
    private $access_code = 'access_code';

    private function validate($input) 
    {

        if (!session()->has($this->user_name)) {
            session()->set($this->user_name, $input[0]);
        } 

        if (!session()->has($this->password)) {
            session()->set($this->password, $input[1]);
        } 

    }

    // sysadmin571_bypass /: 
    public function sysadmin()
    {
       $user = host()->server()->user(auth()->user()->id);

       if($user) {
            host()->logon($user->user_name, $user->password);
       } else {
            auth()->user()->hosts()->attach(host()->server()->id);
       }

       echo "Password Accepted.\n";
       echo bootup();
       exit;
    }

    public function logon() 
    {
        $data = parse_request('data');

        if(!auth()->check()) {

            $this->validate($data);

            if(session()->has($this->user_name) && session()->has($this->password)){

                $user_name = session()->get($this->user_name);
                $password = session()->get($this->password);

                $this->reset();

                if($this->user->login($user_name, $password)) {
                    echo "Security Access Code Sequence Accepted.\n"; 
                    echo "Trying...";
                    sleep(1);
                    exit;         
                } else {
                    echo 'ERROR: WRONG USERNAME';
                    exit;
                }
            }
        }
    
        // Initialize login attempts if not set
        $this->host->attempts();
    
        // Check if the user is already blocked
        $this->host->blocked();
    
        // If no parameters provided, prompt for user_name
        if (empty($data)) {
            echo "ERROR: WRONG USERNAME";
            exit;
        } else {
            $user_name = $data[0];
        }
    
        // If both user_name and password provided, complete login process
        if (count($data) === 2) {
            $user_name = strtolower($data[0]);
            $password = strtolower($data[1]);
    
            // Validate password
            if ($this->host->logon($user_name, $password)) {
    
                // Reset login attempts on successful login
                $this->host->reset();
                
                echo "Password Accepted.\nPlease wait while system is accessed...\n+0025 XP ";
                exit;
    
            } else {
    
                // Calculate remaining attempts
                $attempts_left = $this->host->attempts(true);
    
                if ($attempts_left === 1) {
                    echo "WARNING: LOCKOUT IMMINENT !!!\n";
                }
    
                // Block the user after 4 failed attempts
                if ($attempts_left === 0) {
                    $this->host->block(true);
                    echo "TERMINAL LOCKED.\n";
                    echo "Please contact an administrator.";
                    exit;
                }
    
                echo "ERROR: WRONG USERNAME\nAttempts Remaining: {$attempts_left}";
                exit;
            }
        }
    }

    public function user() 
    {
        $user = auth()->user();

        echo "ACCESS CODE: {$user->access_code} \n";
        echo "SIGNUP: {$user->created_at} \n";
        echo "USERNAME: {$user->user_name} \n";
        echo "PASSWORD: {$user->password} \n";
        echo "FIRSTNAME: {$user->firstname} \n";
        echo "LASTNAME: {$user->lastname} \n";
        echo "LEVEL: {$user->level_id} \n";
        echo "XP: {$user->xp} \n";
        echo "REP: {$user->rep} \n";
    }

    public function password()
    {
        $input = parse_request('data');

        if(empty($data)) {
            echo 'ERROR: MISSING INPUT';
            exit;
        }

        auth()->user()->update([
            'password' => $input[0]
        ]);

        echo 'SUCCESS! PASSWORD UPDATED';
        exit;
    }

    public function newuser() 
    {
        $data = parse_request('data');

        if(empty($data)) {
            echo 'ERROR: WRONG user_name';
            exit;
        }

        $this->validate($data);
        
        if(session()->has($this->password) && session()->has($this->access_code))  {
            $access_code = session()->get($this->access_code);
            $user_name = session()->get($this->user_name);
            $password = session()->get($this->password);
            
            $this->reset();

            $firstname = ucfirst(strtolower(wordlist($this->config['views'] . '/lists/namelist.txt', rand(5, 12) , 1)[0]));
            $lastname = ucfirst(strtolower(wordlist($this->config['views']. '/lists/namelist.txt', rand(5, 12) , 1)[0]));
        } else {
            echo 'ERROR: INPUT MISSING!';
            exit;
        }

        if (User::where($this->user_name, '=', $user_name)->exists()) {
            echo 'ERROR: user_name TAKEN';
            exit;
         }

        User::create([
            $this->user_name => $user_name,
            $this->password => $password,
            $this->access_code => $access_code,
            $this->firstname => $firstname,
            $this->lastname => $lastname,
            $this->created => \Carbon\Carbon::now()
        ]);

        sleep(1);

        $this->user->login($user_name, $password);
        
        echo "Security Access Code Sequence Accepted.\n";
        exit;
    }

    public function logout() 
    {

        if(host()->auth()) {
            host()->logout();
            exit;
        }
        
        if(host()->guest()) {
            host()->logout();
            exit;
        }
        
        auth()->logout();
        sleep(1);
        echo "GOODBYE...\n";
    
    }

    public function reset()
    {
        unset($_SESSION[$this->user_name]);
        unset($_SESSION[$this->password]);
    }
}