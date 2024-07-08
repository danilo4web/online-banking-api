<?php

namespace Tests\Integration\App\Http\Controllers\API;

use App\Models\Account;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CryptoPurchaseTest extends TestCase
{
    use RefreshDatabase;

    protected $user;
    protected $account;

    public function setUp(): void
    {
        parent::setUp();

        $this->user = User::factory()->create();
        $this->account = Account::factory()->create();
    }

    public function test_should_purchase_crypto_with_current_account_balance()
    {
        $this->actingAs($this->user, 'api');

        $payload = [
            'amount' => 1100
        ];

        $this->postJson('/api/account/deposit', $payload)
            ->assertStatus(200)
            ->assertJson(['balance' => 1100]);

        $this->postJson('/api/btc/purchase', $payload)
            ->assertStatus(200)
            ->assertJson(['balance' => 0]);
    }
}
