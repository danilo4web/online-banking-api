<?php

namespace Tests\Integration\App\Http\Controllers\API;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AccountCreateTest extends TestCase
{
    use RefreshDatabase;

    public function setUp(): void
    {
        parent::setUp();
    }

    public function test_should_create_new_account()
    {
        $payload = [
            'name' => 'Fulano da Silva',
            'email' => 'danilo4web1@gmail.com',
            'password' => 'fulano123'
        ];

        $this->postJson('/api/account', $payload)
            ->assertStatus(201);
    }
}
