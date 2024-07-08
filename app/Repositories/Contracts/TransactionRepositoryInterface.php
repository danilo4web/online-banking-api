<?php

namespace App\Repositories\Contracts;

interface TransactionRepositoryInterface
{
    public function addCredit(int $accountId, float $amount, string $description = null): void;

    public function addDebit(int $accountId, float $amount, string $description = null): void;

    public function getHistoryByAccountId(int $accountId, string $dataStart, string $dateEnd): array;
}
