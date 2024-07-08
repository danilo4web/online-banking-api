<?php

namespace App\Gateway;

use Illuminate\Support\Facades\Http;

class BitcoinGateway
{
    protected string $apiUrl;

    public function __construct()
    {
        $this->apiUrl = config('services.gateways.bitcoin_url');
    }

    public function quote(): array
    {
        $response = Http::get($this->apiUrl . '/BTC/ticker/');

        if (!$response->successful()) {
            throw new \Exception('Failed to retrieve Bitcoin value.');
        }

        return $response->json();
    }
}
