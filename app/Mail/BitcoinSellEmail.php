<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class BitcoinSellEmail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(
        protected string $messageContent
    ) {

    }

    public function build()
    {
        return $this->view('bitcoin-purchase')
            ->subject('Bank - Bitcoin Sold Notification')
            ->with([
                'messageContent' => $this->messageContent,
            ]);
    }
}
