<?php

namespace App\Console\Commands;

use App\Gateway\BitcoinGateway;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Redis;

class StoreBitcoinQuoteCron extends Command
{
    protected $signature = 'bitcoin:store-quote';
    protected $description = 'Store Bitcoin quote in Redis every 10 minutes';

    public function __construct(protected BitcoinGateway $bitcoinGateway)
    {
        parent::__construct();
    }

    public function handle(): void
    {
        Log::info("StoreBitcoinQuoteCron Job is running at " . now());

        try {
            $quote = $this->bitcoinGateway->quote();
            $currentTimestamp = now()->timestamp;
            $expiresIn90days = 24 * 60 * 60 * 90;

            Redis::set("bitcoin_quote:{$currentTimestamp}", json_encode($quote));
            Redis::expire("bitcoin_quote:{$currentTimestamp}", $expiresIn90days);

            Log::info('Bitcoin quote stored successfully.');
        } catch (\Exception $e) {
            Log::error($e->getMessage());
        }
    }
}
