<?php

namespace Tests\Integration\App\Http\Controllers\API;

use App\Models\Account;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class DepositTest extends TestCase
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

    public function test_should_deposit_in_account()
    {
        $this->actingAs($this->user, 'api');

        $payload = [
            'amount' => 1100
        ];

        $this->postJson('/api/account/deposit', $payload)
            ->assertStatus(200)
            ->assertJsonStructure(['balance']);
    }
}
