<?php

namespace App\Validation\Rules;

use App\Models\User;
use Respect\Validation\Rules\AbstractRule;

class EmailExists extends AbstractRule {

    public function validate($input) 
    {
        return User::where('email', $input)->count() === 0;
    }

}