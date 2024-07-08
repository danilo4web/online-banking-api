<?php

namespace App\Services;

use App\Exceptions\AccountNotCreatedException;
use App\Repositories\Contracts\AccountRepositoryInterface;
use App\Repositories\Eloquent\TransactionRepository;

class AccountService
{
    public function __construct(
        protected AccountRepositoryInterface $accountRepository,
        protected TransactionRepository $transactionRepository,
        protected UserService $userService
    ) {

    }

    public function create(array $data): array
    {
        $account = $this->accountRepository->addAccount($data);

        if (!$account) {
            throw new AccountNotCreatedException();
        }

        return $account->toArray();
    }

    public function deposit(float $amount, string $description = null): float
    {
        $user = $this->userService->getUser();
        $account = $this->accountRepository->findAccountByUser($user->id);
        $deposit = $this->accountRepository->deposit($amount, $account->id);

        $this->transactionRepository->addCredit($account->id, $amount, $description);

        return $deposit;
    }

    public function withdraw(float $amount, $cryptoPurchase = false): float
    {
        $user = $this->userService->getUser();
        $account = $this->accountRepository->findAccountByUser($user->id);
        $withdraw = $this->accountRepository->withdraw($amount, $account->id);

        $this->transactionRepository->addDebit($account->id, $amount, $cryptoPurchase ? 'Crypto Purchase' : 'Withdraw');

        return $withdraw;
    }

    public function getBalance(): float
    {
        $user = $this->userService->getUser();

        return $this->accountRepository->getBalance($user->id);
    }

    public function getTransactions(string $dateStart, string $dateEnd, int $userId)
    {
        $account = $this->accountRepository->findAccountByUser($userId);

        return $this->transactionRepository->getHistoryByAccountId($account->id, $dateStart, $dateEnd);
    }
}
