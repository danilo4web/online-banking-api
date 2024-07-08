<?php

namespace App\Repositories\Contracts;

use App\Models\Account;

interface AccountRepositoryInterface
{
    public function getBalance(int $userId): float;

    public function addAccount(array $attributes);

    public function findAccountByUser(int $userId): ?Account;
}
