<?php

namespace App\Repositories\Eloquent;

use App\Models\BitcoinWallet;
use App\Repositories\Contracts\BitcoinWalletRepositoryInterface;

class BitcoinWalletRepository implements BitcoinWalletRepositoryInterface
{
    protected $model = BitcoinWallet::class;

    public function create(array $attributes)
    {
        return $this->model::create($attributes);
    }

    public function update(int $id, array $attributes): bool
    {
        return $this->model::find($id)->update($attributes);
    }

    public function findByUser(int $userId): ?BitcoinWallet
    {
        return $this->model::where('user_id', $userId)->first();
    }
}
