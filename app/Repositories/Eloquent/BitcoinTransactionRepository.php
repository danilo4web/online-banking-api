<?php

namespace App\Repositories\Eloquent;

use App\Models\BitcoinTransaction;
use App\Repositories\Contracts\BitcoinTransactionRepositoryInterface;

class BitcoinTransactionRepository implements BitcoinTransactionRepositoryInterface
{
    protected $model = BitcoinTransaction::class;

    public function save(array $attributes): BitcoinTransaction
    {
        return $this->model::create($attributes);
    }

    public function getByWallet(int $walletId)
    {
        return $this->model::where('bitcoin_wallet_id', $walletId)->get();
    }

    public function getAmountOfDayByType(string $day, string $type): float
    {
        return $this->model::where('type', $type)
            ->whereDate('created_at', $day)
            ->sum('btc_amount');
    }
}
