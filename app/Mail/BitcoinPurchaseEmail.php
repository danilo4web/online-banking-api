<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class BitcoinPurchaseEmail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(
        protected string $messageContent
    ) {

    }

    public function build()
    {
        return $this->view('bitcoin-purchase')
            ->subject('Bank - Bitcoin Purchase Notification')
            ->with([
                'messageContent' => $this->messageContent,
            ]);
    }
}
