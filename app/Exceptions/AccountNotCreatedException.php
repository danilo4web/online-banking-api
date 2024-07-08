<?php

namespace App\Exceptions;

use Exception;

class AccountNotCreatedException extends Exception
{
    protected $message = 'Account not created';
}
