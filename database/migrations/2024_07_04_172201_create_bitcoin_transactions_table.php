<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('bitcoin_transactions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('bitcoin_wallet_id')->constrained('bitcoin_wallets')->onDelete('cascade');
            $table->enum('type', ['buy', 'sell']);
            $table->decimal('btc_amount', 27, 18);
            $table->decimal('btc_price', 27, 18);
            $table->decimal('price', 9, 2);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('bitcoin_wallet_transactions');
    }
};
