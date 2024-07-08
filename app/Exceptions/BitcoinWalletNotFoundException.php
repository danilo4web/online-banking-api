<?php

namespace App\Exceptions;

use Exception;

class BitcoinWalletNotFoundException extends Exception
{
    protected $message = 'Bitcoin wallet not found';
}
