<?php

namespace App\Repositories\Contracts;

use App\Models\BitcoinTransaction;

interface BitcoinTransactionRepositoryInterface
{
    public function save(array $attributes): BitcoinTransaction;

    public function getByWallet(int $walletId);

    public function getAmountOfDayByType(string $day, string $type): float;
}
