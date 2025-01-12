<?php

namespace App\System\Email;

use Lib\Controller;

use App\System\Email\EmailModel as Email;

use App\User\UserService as User;
use App\User\UserModel as Users;

use App\Host\HostModel as Hosts;

class EmailController extends Controller
{
    public function mail()
    {
        $data = parse_request('data');

        $command = $data[0];
        if(isset($data[1])) {
            unset($data[0]);
            $args = $data;
            $options = explode('<', trim(request()->get('data')));
        } else {
            $args = '';
        }

       switch ($command) {
        case ($command == '-l' || $command == 'list'):
            $this->list();
            break;

        case ($command == '-s' || $command == 'send'):
            $this->send($options);
            break;

        case ($command == '-r' || $command == 'read'):
            $this->read($args);
            break;

        case ($command == '-d' || $command == 'delete'):
            $this->delete($args);
            break;
        
        default:
            $this->list();
            break;
       }
      
    }

    public function list()
    {
        $id = 0;
        $emails = Email::where('recipient', User::auth())->get();

        foreach ($emails as $email) {
            $id++;
            $sender = Users::find($email->sender);
            echo "$email->id [$sender->user_name, $email->created_at, $email->subject]\n";
        }
            
    }

    public function send($data)
    {
        if(empty($data)) {
            $id = 1;
            $emails = Email::where('sender', User::username())->get();

            foreach ($emails as $email) {
                $id++;
                echo "$email->id [$email->recipient, $email->created_at, $email->subject]\n";
            }

            exit;
        }

        $options = explode(' ',trim($data[0]));

        $body = trim($data[1]);
        $subject = $options[1];
        $sender = User::username();

        if(isEmail($options[2])) {
            $email = $options[2];
            $mail = explode('@', $email);
            $username = $mail[0];
            $host = $mail[1];
        } else {
            $username = $options[2];
        }

        if(Users::where('user_name', $username)->exists()) {
            
            if(Hosts::where('host_name', $host)->exists()) {
                $recipient = "$username@$host";
            } else {
                $recipient = $username;
            }

            Email::create([
                'sender' => $sender,
                'recipient' => $recipient,
                'subject' => $subject,
                'body' => $body
            ]);

            echo 'Email Sent.';
        } else {
            echo 'ERROR: Unknown Recipient.';
        }
    }

    public function read($data)
    {
        $email = Email::where('id', $data)->first();
        
        if(!empty($email)) {

            $from = Users::find($email->sender)->user_name;
            $to = Users::find($email->recipient)->user_name;
        
            if(!$from) {
                $from = 'UNKNOWN';
            }

            if(!$to) {
                $to = 'UNKNOWN';
            }

            echo <<< EOT
            From: $from
            To: $to
            Date: $email->created_at
            Subject: $email->subject

            $email->body
            EOT;
        } else {
            echo "ERROR: Unknown Email.";
        }

    }

    public function delete($data)
    {
        if(!empty($data)) {
            Email::where('id', $data)->where('recipient', User::auth())->delete();
            echo 'Email Deleted.';
        } else {
            echo 'ERROR: Unknown Email.';
        }
    }

}