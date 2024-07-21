<?php

namespace Tests\Feature;

use App\Models\User;
use App\Services\CurrencyService;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\Mock\CurrencyServiceMock;
use Tests\TestCase;

class CurrencyTest extends TestCase
{
    use DatabaseTransactions;

    protected function setUp(): void
    {
        parent::setUp();
        $this->app->bind(CurrencyService::class, CurrencyServiceMock::class);
    }

    private function authUser(): void
    {
        $user = User::factory()->create();
        $accessToken = $user->createToken('token-name')->accessToken;
        $this->withHeaders([
            'Authorization' => 'Bearer ' . $accessToken
        ]);
    }

    public function testGetCurrencies(): void
    {
        $this->authUser();
        $response = $this->get(route('currency.list'));
        $response->assertStatus(200);
        $this->assertIsArray($response->json());
    }

    public function testGetExchangeRate(): void
    {
        $this->authUser();
        $response = $this->get(route('currency.exchange-rate', ['currency' => 'USD']));
        $response->assertStatus(200)
            ->assertJsonStructure(['rate', 'name']);
    }

    public function testConvertCurrency(): void
    {
        $this->authUser();
        $response = $this->get(route('currency.convert', [
            'from' => 'USD',
            'to' => 'EUR',
            'amount' => 100,
        ]));

        $response->assertStatus(200)
            ->assertJsonStructure(['converted_amount']);
    }
}
