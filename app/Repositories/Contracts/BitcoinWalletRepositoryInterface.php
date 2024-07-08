<?php

namespace App\Repositories\Contracts;

use App\Models\BitcoinWallet;

interface BitcoinWalletRepositoryInterface
{
    public function create(array $attributes);

    public function update(int $id, array $attributes): bool;

    public function findByUser(int $userId): ?BitcoinWallet;
}
