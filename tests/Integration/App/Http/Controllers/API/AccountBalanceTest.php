<?php

namespace Tests\Integration\App\Http\Controllers\API;

use App\Models\Account;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AccountBalanceTest extends TestCase
{
    use RefreshDatabase;

    protected $user;

    public function setUp(): void
    {
        parent::setUp();

        $this->user = User::factory()->create();
    }

    public function testShouldGetBalance()
    {
        $this->actingAs($this->user, 'api');

        $account = Account::factory()->create();

        $this->getJson("/api/account/balance/")
            ->assertJson([
                'balance' => $account->balance,
            ])
            ->assertStatus(200);
    }
}
