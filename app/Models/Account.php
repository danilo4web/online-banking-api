<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Account extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'balance',
        'account_number'
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function balance(Account $account): float
    {
        return $account->balance;
    }

    public function transactions(): HasMany
    {
        return $this->hasMany(Transaction::class);
    }
}
