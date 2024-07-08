<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class DepositEmail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(
        protected string $messageContent
    ) {

    }

    public function build()
    {
        return $this->view('deposit')
            ->subject('Bank - Deposit Notification')
            ->with([
                'messageContent' => $this->messageContent,
            ]);
    }
}
