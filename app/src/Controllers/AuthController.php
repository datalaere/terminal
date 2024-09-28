<?php

namespace App\Controllers;

use App\Models\User;
use App\Services\Controller;
use Respect\Validation\Validator as v;

class AuthController extends Controller
{

    private $email = 'email';
    private $password = 'password';
    private $firstname = 'firstname';
    private $lastname = 'lastname';
    private $created = 'created_at';

    private function validate($data) {

        $input = explode(' ', trim($data));

        if (count($input) >= 1 && strlen($input[0]) === 27 && preg_match('/^[AXYZ01234679-]+$/', $input[0])) {

            $user[$this->password] = $input[0];
            $user[$this->email] = $input[1];

        } else {
            return false;
        }

        return $user;
    }

    public function login($request, $response) 
    {
        $data = $request->getParam('data');

        if(empty($data)) {
            return 'ERROR: Missing parameters.';
        }

        $user = $this->validate($data);

        if(!$user) {
            return 'ERROR: Missing parameters.';
        } else {
            $password = $user[$this->password];
            $email = $user[$this->email];
        }

        sleep(1);

        if($this->auth->attempt($email, $password)) {
            $username = $this->auth->user()->username;
            return "ACCESS CODE: {$password}\nEMPLOYEE ID: {$username}\n";            
        }

    }

    public function register($request, $response) 
    {
        
        $data = $request->getParam('data');

        if(empty($data)) {
            return 'ERROR: Missing parameters.';
        }

        $user = $this->validate($data);

        if(!$user) {
            return 'ERROR: Missing parameters.';
        } else {
            $password = $user[$this->password];
            $email = $user[$this->email];
            $firstname = ucfirst(strtolower(wordlist($this->settings['path'] . '/app/storage/text/namelist.txt', rand(5, 12) , 1)[0]));
            $lastname = ucfirst(strtolower(wordlist($this->settings['path']. '/app/storage/text/namelist.txt', rand(5, 12) , 1)[0]));
        }

        if (User::where($this->email, '=', $email)->exists()) {
            return 'ERROR: User taken!';
         }

        $user_id = User::insertGetId([
            $this->password => $password,
            $this->email => $email,
            $this->firstname => $firstname,
            $this->lastname => $lastname,
            $this->created => \Carbon\Carbon::now()
        ]);

        $username = 'PE-' . strtoupper(random_username($firstname, $user_id));

        $user = User::find($user_id);
        $user->username = $username;
        $user->save();

        sleep(1);

        $this->auth->attempt($username, $password);

        return "ACCESS CODE: {$password}\nEMPLOYEE ID: {$username}\n";
    }

    public function logout() {

        $this->auth->logout();
    
        return "DISCONNECTING from PoseidoNET...\n";
    }
}