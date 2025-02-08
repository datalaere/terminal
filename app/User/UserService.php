<?php

namespace App\User;

use App\User\UserModel as User;
use Lib\Session;
use Lib\Cache;

class UserService extends User
{
    private static $auth = 'user';
    private static $uplink = 'uplink';
    private static $blocked = 'blocked';

    public static function key()
    {
        return self::$auth . self::auth();
    }

    public static function data() 
    {
        if(self::auth()) {
            return Cache::remember(self::key(), fn() => User::find(self::auth()));
        }
        return false;
    }

    public static function username()
    {       
        if(self::data()) {
            return self::data()->username;
        } else {
            return false;
        }
    }

    public static function uplink($action = true)
    {
        $uplink = self::$uplink;

        if($action) {
            if(!Session::has($uplink)) {
                Session::set($uplink, true);
            }
        } else {
            if(Session::has($uplink)) {
                Session::remove($uplink);
            }
        }

    }

    public static function uplinked()
    {
        if(Session::has(self::$uplink)) {
            return true;
        } else {
            return false;
        }
    }

    public static function check()
    {
        return self::auth();
    }

    public static function attempt($id)
    {
        Cache::forget(self::key());
        return Session::set(self::$auth, $id);
    }

    public static function id()
    {
        return self::data()->id;
    }

    public static function auth() 
    {
        if(Session::has(self::$auth)) {
            return Session::get(self::$auth);
        }
        return false;
    }

    public static function blocked($block = false)
    {

        if($block) {
            Session::set(self::$blocked, true);
        }

        if (Session::has(self::$blocked)) {
            echo <<< EOT
            --CONNECTION TERMINATED--
            EOT;
            exit;
        }

        if(!$block) {
            Session::remove(self::$blocked);
        }
    }

    public static function login($emailOrUsername, $password) 
    {
        $user = User::where('email', $emailOrUsername)
                    ->orWhere('username', $emailOrUsername)
                    ->first();

        if (!$user) {
            return false;
        }

        if ($user->password == $password OR $user->code == $password) {
            Session::set(self::$auth, $user->id);
            if(empty(self::data()->last_login)) {
                self::data()->update(['last_login' => now()]);
            }
            self::data()->update(['ip' => remote_ip()]);
            return true;
        }

        return false;
    }

    public static function logout() 
    {
        if(self::auth()) {
            sleep(1);
            self::data()->update(['last_login' => now()]);
            Cache::forget(self::key());
            self::uplink(false);
            Session::remove(self::$auth);
        }
        echo "Goodbye.\n\n";
    }

    public static function count()
    {
        return User::count();
    }    

}