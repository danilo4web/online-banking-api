<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class BitcoinTransaction extends Model
{
    use HasFactory;

    public const TYPE_BUY = 'buy';
    public const TYPE_SELL = 'sell';

    protected $fillable = [
        'bitcoin_wallet_id',
        'type',
        'btc_amount',
        'btc_price',
        'price'
    ];

    public function wallet(): BelongsTo
    {
        return $this->belongsTo(BitcoinWallet::class);
    }
}
