<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use GuzzleHttp\Client;

class CurrencyController extends Controller
{
    public function getCurrencies()
    {
        $client = new Client();

        try {
            $response = $client->get('https://www.cbr.ru/scripts/XML_valfull.asp');
            $responseBody = $response->getBody();
            $data = simplexml_load_string($responseBody);

            if ($data === false) {
                return response()->json(['error' => 'Ошибка обработки XML'], 500);
            }

            $currencies = [];
            foreach ($data as $valute) {
                $currencies[] = [
                    'code' => (string) $valute->Name,
                    'Nominal' => (string) $valute->Nominal,
                    'ISO_Char_Code' => (string) $valute->ISO_Char_Code,
                ];
            }

            if (empty($currencies)) {
                return response()->json(['error' => 'Нет валют в ответе'], 404);
            }

            return response()->json($currencies);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function getExchangeRate($currency)
    {
        $client = new Client();
        $response = $client->get('https://www.cbr.ru/scripts/XML_daily.asp');
        $data = simplexml_load_string($response->getBody());

        foreach ($data->Valute as $valute) {
            if ($valute->CharCode == strtoupper($currency)) {
                return response()->json([
                    'rate' => (float)$valute->Value,
                    'name' => (string)$valute->Name,
                ]);
            }
        }

        return response()->json(['error' => 'Currency not found'], 404);
    }

    public function convertCurrency(Request $request)
    {
        $request->validate([
            'from' => 'required|string',
            'to' => 'required|string',
            'amount' => 'required|numeric',
        ]);

        $fromRate = $this->getExchangeRate($request->from);
        $toRate = $this->getExchangeRate($request->to);

        if ($fromRate->getContent() === '{"error":"Currency not found"}' || $toRate->getContent() === '{"error":"Currency not found"}') {
            return response()->json(['error' => 'Currency not found'], 404);
        }

        $amountInRubles = $request->amount / $fromRate->original['rate'];
        $convertedAmount = $amountInRubles * $toRate->original['rate'];

        return response()->json([
            'converted_amount' => round($convertedAmount, 2),
        ]);
    }
}
