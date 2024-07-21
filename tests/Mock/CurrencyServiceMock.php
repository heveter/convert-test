<?php

namespace Tests\Mock;

use App\Services\CurrencyService;

class CurrencyServiceMock extends CurrencyService
{
    public function getCourses(): array
    {
        return [
            'USD' => [
                'currency' => 'USD',
                'nominal' => 1,
                'value' => 88,
                'rate' => 88,
                'name' => 'USD'
            ],
            'EUR' => [
                'currency' => 'EUR',
                'nominal' => 1,
                'value' => 93,
                'rate' => 93,
                'name' => 'EUR',
            ],
        ];
    }
}