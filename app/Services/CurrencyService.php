<?php

namespace App\Services;

use App\Exceptions\BaseMessageException;
use Illuminate\Support\Facades\Http;

class CurrencyService
{
    const string BASE_URL = 'https://www.cbr-xml-daily.ru/daily_json.js';

    /**
     * @throws BaseMessageException
     */
    public function getCourses(): array
    {
        $response = Http::get(self::BASE_URL);

        if (!$response->ok()) {
            throw new BaseMessageException('Ошибка обработки');
        }

        $responseDecode = $response->json();

        if (empty($responseDecode)) {
            throw new BaseMessageException('Ошибка обработки');
        }

        $courses = [];

        foreach ($responseDecode['Valute'] as $item) {
            $courses[$item['CharCode']] = [
                'currency' => $item['CharCode'],
                'nominal' => $item['Nominal'],
                'value' => $item['Value'],
                'rate' => $item['Value'] / $item['Nominal'],
                'name' => $item['Name'],
            ];
        }

        return $courses;
    }
}