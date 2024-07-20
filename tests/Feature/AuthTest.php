<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\User;

class AuthTest extends TestCase
{
    use RefreshDatabase;

    public function testUserRegistration()
    {
        $response = $this->post('/api/register', [
            'name' => 'Test User',
            'email' => 'test@example.com',
            'password' => 'password',
        ]);

        $response->assertStatus(201);
        $this->assertDatabaseHas('users', ['email' => 'test@example.com']);
    }

    public function testUserLogin()
    {
        $user = User::create([
            'name' => 'Test User',
            'email' => 'test@example.com',
            'password' => bcrypt('password'),
        ]);

        $response = $this->post('/api/login', [
            'email' => 'test@example.com',
            'password' => 'password',
        ]);

        $response->assertStatus(200)
            ->assertJsonStructure(['token']);
    }

    public function testGetCurrencies()
    {
        $response = $this->get('/api/currencies');
        $response->assertStatus(200);
        $this->assertIsArray($response->json());
    }

    public function testGetExchangeRate()
    {
        $response = $this->get('/api/exchange-rate/USD');
        $response->assertStatus(200)
            ->assertJsonStructure(['rate', 'name']);
    }

    public function testConvertCurrency()
    {
        $response = $this->post('/api/convert', [
            'from' => 'USD',
            'to' => 'EUR',
            'amount' => 100,
        ]);

        $response->assertStatus(200)
            ->assertJsonStructure(['converted_amount']);
    }
}
