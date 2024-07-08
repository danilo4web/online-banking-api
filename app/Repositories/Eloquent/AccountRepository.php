<?php

namespace App\Repositories\Eloquent;

use App\Models\Account;
use App\Repositories\Contracts\AccountRepositoryInterface;

class AccountRepository implements AccountRepositoryInterface
{
    protected $model = Account::class;

    public function findAccountByUser(int $userId): ?Account
    {
        return $this->model::where('user_id', $userId)->first();
    }

    public function getBalance(int $userId): float
    {
        return $this->model::where('user_id', $userId)->value('balance');
    }

    public function addAccount(array $attributes)
    {
        $attributes['account_number'] = $this->generateAccountNumber();
        $attributes['balance'] = 0;

        return $this->model::create($attributes);
    }

    private function generateAccountNumber(): string
    {
        return 'BK-' . mt_rand(1000000000, 9999999999);
    }

    public function deposit(float $amount, int $accountId): float
    {
        $account = $this->model::find($accountId);
        $account->balance += $amount;
        $account->save();

        return $account->balance;
    }

    public function withdraw(float $amount, int $accountId): float
    {
        $account = $this->model::find($accountId);
        $account->balance -= $amount;
        $account->save();

        return $account->balance;
    }
}
