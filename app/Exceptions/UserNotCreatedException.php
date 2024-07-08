<?php

namespace App\Exceptions;

use Exception;

class UserNotCreatedException extends Exception
{
    protected $message = 'User not created';
}
