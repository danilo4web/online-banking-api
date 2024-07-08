<?php

namespace Tests\Integration\App\Http\Controllers\API;

use App\Models\Account;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class LoginTest extends TestCase
{
    use RefreshDatabase;

    public function setUp(): void
    {
        parent::setUp();
    }

    public function test_should_login()
    {
        User::factory()->create([
            'name' => 'Fulano da Silva',
            'email' => 'danilo4web@gmail.com',
            'password' => 'fulano123'
        ]);

        $payload = [
            'email' => 'danilo4web@gmail.com',
            'password' => 'fulano123'
        ];

        $this->postJson('/api/login', $payload)
            ->assertStatus(200)
            ->assertJsonStructure(['token']);
    }

    public function test_should_not_login_with_invalid_credentials()
    {
        $payload = [
            'email' => 'invalid@gmail.com',
            'password' => 'pass'
        ];

        $this->postJson('/api/login', $payload)
            ->assertStatus(401);
    }
}
