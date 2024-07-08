<?php

namespace App\Repositories\Eloquent;

use App\Models\Account;
use App\Models\Transaction;
use App\Repositories\Contracts\TransactionRepositoryInterface;

class TransactionRepository implements TransactionRepositoryInterface
{
    protected $model = Transaction::class;

    public function addCredit(int $accountId, float $amount, string $description = null): void
    {
        Transaction::create([
            'amount' => $amount,
            'type' => Transaction::CREDIT,
            'account_id' => $accountId,
            'description' => $description,
        ]);
    }

    public function addDebit(int $accountId, float $amount, string $description = null): void
    {
        Transaction::create([
            'amount' => $amount,
            'type' => Transaction::DEBIT,
            'account_id' => $accountId,
            'description' => $description
        ]);
    }

    public function getHistoryByAccountId(int $accountId, string $dataStart, string $dateEnd): array
    {
        return Transaction::where('account_id', $accountId)
            ->where('created_at', '>=', $dataStart)
            ->where('created_at', '<=', $dateEnd)
            ->orderBy('created_at', 'desc')
            ->select('amount', 'type', 'description', 'created_at')
            ->get()
            ->toArray();
    }
}
