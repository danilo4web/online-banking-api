<?php

namespace App\Exceptions;

use Exception;

class InsufficientBitcoinException extends Exception
{
    protected $message = 'Insufficient Bitcoin Balance';
}
