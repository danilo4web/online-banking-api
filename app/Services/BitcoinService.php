<?php

namespace App\Services;

use App\Exceptions\BitcoinWalletNotFoundException;
use App\Exceptions\InsufficientBitcoinException;
use App\Gateway\BitcoinGateway;
use App\Jobs\SendEmailJob;
use App\Mail\BitcoinPurchaseEmail;
use App\Mail\BitcoinSellEmail;
use App\Models\BitcoinTransaction;
use App\Repositories\Contracts\BitcoinTransactionRepositoryInterface;
use App\Repositories\Contracts\BitcoinWalletRepositoryInterface;
use App\Repositories\Contracts\TransactionRepositoryInterface;
use Carbon\Carbon;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Redis;
use Illuminate\Support\Facades\DB;
use App\Exceptions\InsufficientBalanceException;

class BitcoinService
{
    public function __construct(
        protected BitcoinGateway $bitcoinGateway,
        protected TransactionRepositoryInterface $transactionRepository,
        protected BitcoinWalletRepositoryInterface $bitcoinWalletRepository,
        protected BitcoinTransactionRepositoryInterface $bitcoinTransactionRepository,
        protected AccountService $accountService,
        protected UserService $userService
    ) {

    }

    public function getCurrentPrice(): float
    {
        return $this->bitcoinGateway->quote()['ticker']['buy'];
    }

    public function getQuote(): array
    {
        return $this->bitcoinGateway->quote();
    }

    public function buyBitcoin(float $amountInBRL): float
    {
        try {
            DB::beginTransaction();

            $user = $this->userService->getUser();
            $currentBalance = $this->accountService->getBalance();

            if ($currentBalance < $amountInBRL) {
                throw new InsufficientBalanceException();
            }

            $bitcoin = $this->bitcoinGateway->quote();
            $bitcoinAmountBought = $amountInBRL / $bitcoin['ticker']['sell'];
            $newBalance = $this->accountService->withdraw($amountInBRL, true);

            $bitcoinWallet = $this->findOrCreateBitcoinWallet($user['id']);
            $this->bitcoinWalletRepository->update($bitcoinWallet['id'], [
                'balance' => $bitcoinWallet['balance'] + $bitcoinAmountBought
            ]);
            $this->createBitcoinTransaction($bitcoinWallet['id'], $bitcoinAmountBought, $bitcoin['ticker']['sell'], $amountInBRL);

            $message = "You have invested R$ {$amountInBRL} in {$bitcoinAmountBought} bitcoins in " . Carbon::now()->format('Y-m-d');

            dispatch(new SendEmailJob($user->email, (new BitcoinPurchaseEmail($message)), $message));

            DB::commit();
        } catch (InsufficientBalanceException $e) {
            DB::rollBack();
            throw new InsufficientBalanceException();
        } catch (\Exception $e) {
            DB::rollBack();
            throw new \Exception('Purchase not complete, please try again.', $e->getCode());
        }

        return $newBalance;
    }


    public function sellBitcoin(float $amountInBTC): float
    {
        try {
            DB::beginTransaction();

            $user = $this->userService->getUser();
            $bitcoinWallet = $this->bitcoinWalletRepository->findByUser($user['id']);

            if (!$bitcoinWallet) {
                throw new BitcoinWalletNotFoundException();
            }

            if ($bitcoinWallet['balance'] < $amountInBTC) {
                throw new InsufficientBitcoinException();
            }

            $bitcoin = $this->bitcoinGateway->quote();
            $amountInBRL = $amountInBTC * $bitcoin['ticker']['buy'];

            $this->bitcoinWalletRepository->update($bitcoinWallet['id'], [
                'balance' => $bitcoinWallet['balance'] - $amountInBTC
            ]);

            $newBalance = $this->accountService->deposit($amountInBRL, 'Bitcoin Sold');
            $this->createBitcoinTransaction($bitcoinWallet['id'], -$amountInBTC, $bitcoin['ticker']['buy'], $amountInBRL);

            $formatedAmountInBRL = number_format($amountInBRL, 2, ',', '.');
            $message = "You sold {$amountInBTC} bitcoins by {$formatedAmountInBRL} in " . Carbon::now()->format('Y-m-d');

            dispatch(new SendEmailJob($user->email, (new BitcoinSellEmail($message)), $message));

            DB::commit();
        } catch (InsufficientBitcoinException $e) {
            DB::rollBack();
            throw new InsufficientBitcoinException();
        } catch (\Exception $e) {
            DB::rollBack();
            throw new \Exception('Sale not complete, please try again.', $e->getCode());
        }

        return $newBalance;
    }

    private function findOrCreateBitcoinWallet(int $userId): array
    {
        $wallet = $this->bitcoinWalletRepository->findByUser($userId);

        if (!$wallet) {
            $wallet = $this->bitcoinWalletRepository->create([
                'user_id' => $userId,
                'balance' => 0
            ]);
        }

        return $wallet->toArray();
    }

    private function createBitcoinTransaction(int $walletId, float $amount, float $price, float $amountInBRL): void
    {
        $this->bitcoinTransactionRepository->save([
            'bitcoin_wallet_id' => $walletId,
            'btc_amount' => $amount,
            'type' => $amount > 0
                ? BitcoinTransaction::TYPE_BUY
                : BitcoinTransaction::TYPE_SELL,
            'btc_price' => $price,
            'price' => $amountInBRL
        ]);
    }

    public function getInvestmentPosition(int $userId): ?array
    {
        $wallet = $this->bitcoinWalletRepository->findByUser($userId);

        if (!$wallet) {
            throw new BitcoinWalletNotFoundException();
        }

        $transactions = $this->bitcoinTransactionRepository->getByWallet($wallet->id);
        $currentPrice = $this->getCurrentPrice();
        $currentGrossValue = $wallet->balance * $currentPrice;

        $transactions = $this->mapTransactions($transactions, $currentPrice);
        $totalInvestedAmount = $transactions->sum('invested_amount');

        return [
            'transactions' => $transactions->toArray(),
            'total' => [
                'invested_amount' => $totalInvestedAmount,
                'btc_amount' => $wallet->balance,
                'current_gross_value' => $currentGrossValue,
            ]
        ];
    }

    public function getTransactions(int $userId): array
    {
        $wallet = $this->bitcoinWalletRepository->findByUser($userId);
        $transactions = $this->bitcoinTransactionRepository->getByWallet($wallet->id);

        return $transactions->toArray();
    }

    private function mapTransactions(Collection $transactions, float $currentPrice): Collection
    {
        return $transactions->map(function ($transaction) use ($currentPrice) {
            $variationPercentage = $this->calculateVariationPercentage($transaction->btc_price, $currentPrice);

            return [
                'purchase_date' => $transaction->created_at,
                'invested_amount' => $transaction->price,
                'btc_price_at_purchase' => $transaction->btc_price,
                'variation_percentage' => $variationPercentage,
            ];
        });
    }

    private function calculateVariationPercentage(float $purchasePrice, float $currentPrice): float
    {
        return (($currentPrice - $purchasePrice) / $purchasePrice) * 100;
    }

    public function getVolume(string $day): array
    {
        $totalBought = $this->bitcoinTransactionRepository->getAmountOfDayByType($day, 'buy');
        $totalSold = $this->bitcoinTransactionRepository->getAmountOfDayByType($day, 'sell');

        return [
            'total_bought' => $totalBought,
            'total_sold' => $totalSold
        ];
    }

    public function getHistoricalValues(): array
    {
        $keys = Redis::keys('bitcoin_quote:*');
        $now = Carbon::now();

        $quotes = [];
        foreach ($keys as $key) {
            $key = str_replace('laravel_database_', '', $key);
            $storedQuote = Redis::get($key);

            $quoteArray = json_decode($storedQuote, true);
            $quoteTimestamp = Carbon::createFromTimestamp($quoteArray['ticker']['date']);

            if ($quoteTimestamp->diffInHours($now) > 1) {
                continue;
            }

            $quotes[] = [
                'sell' => $quoteArray['ticker']['sell'],
                'buy' => $quoteArray['ticker']['buy'],
                'date' => Carbon::createFromTimestamp($quoteArray['ticker']['date'])->format('Y-m-d H:i:s')
            ];
        }

        return $quotes;
    }
}
