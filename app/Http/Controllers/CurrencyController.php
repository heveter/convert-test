<?php

namespace App\Http\Controllers;

use App\Exceptions\BaseMessageException;
use App\Http\Resources\CurrencyResource;
use App\Services\CurrencyService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use OpenApi\Attributes;
class CurrencyController extends Controller
{
    public function __construct(private readonly CurrencyService $currencyService)
    {
    }

    /**
     * @throws BaseMessageException
     */
    #[Attributes\Get(
        path: '/api/currency/list',
        security: [
            ['AuthHeader' => []]
        ],
        tags: ['Currency'],
        responses: [
            new Attributes\Response(
                response: 200,
                description: 'Ok',
                content: new Attributes\JsonContent(
                    type: 'array',
                    items: new Attributes\Items(
                        properties: [
                            new Attributes\Property(
                                property: 'code',
                                type: 'string',
                                example: "USD"
                            ),
                            new Attributes\Property(
                                property: 'name',
                                type: 'string',
                                example: "США"
                            ),
                            new Attributes\Property(
                                property: 'rate',
                                type: 'number',
                                example: 88.11
                            )
                        ]
                    )
                )
            ),
        ]
    )]
    public function getCurrencies(): JsonResponse
    {
        $courses = $this->currencyService->getCourses();

        return response()->json(CurrencyResource::collection(array_values($courses)));
    }

    /**
     * @throws BaseMessageException
     */
    #[Attributes\Get(
        path: '/api/currency/exchange-rate/{currency}',
        security: [
            ['AuthHeader' => []]
        ],
        tags: ['Currency'],
        parameters: [
            new Attributes\PathParameter(
                name: 'currency',
                example: 'USD'
            )
        ],
        responses: [
            new Attributes\Response(
                response: 200,
                description: 'Ok',
                content: new Attributes\JsonContent(
                    properties: [
                        new Attributes\Property(
                            property: 'code',
                            type: 'string',
                            example: "USD"
                        ),
                        new Attributes\Property(
                            property: 'name',
                            type: 'string',
                            example: "США"
                        ),
                        new Attributes\Property(
                            property: 'rate',
                            type: 'number',
                            example: 88.11
                        )
                    ]
                )
            ),
        ]
    )]
    public function getExchangeRate($currency): JsonResponse
    {
        $courses = $this->currencyService->getCourses();

        if (!isset($courses[$currency])) {
            return response()->json(['error' => 'Currency not found'], 404);
        }

        return response()->json(CurrencyResource::make($courses[$currency]));
    }

    #[Attributes\Get(
        path: '/api/currency/convert',
        security: [
            ['AuthHeader' => []]
        ],
        tags: ['Currency'],
        parameters: [
            new Attributes\QueryParameter(
                name: 'from',
                example: 'USD'
            ),
            new Attributes\QueryParameter(
                name: 'to',
                example: 'EUR'
            ),
            new Attributes\QueryParameter(
                name: 'amount',
                example: 100
            )
        ],
        responses: [
            new Attributes\Response(
                response: 200,
                description: 'Ok',
                content: new Attributes\JsonContent(
                    properties: [
                        new Attributes\Property(
                            property: 'converted_amount',
                            type: 'number',
                            example: 88.11
                        )
                    ]
                )
            ),
        ]
    )]
    public function convertCurrency(Request $request): JsonResponse
    {
        $request->validate([
            'from' => 'required|string',
            'to' => 'required|string',
            'amount' => 'required|numeric',
        ]);

        $courses = $this->currencyService->getCourses();

        $fromRate = $courses[$request->get('from')] ?? null;
        $toRate = $courses[$request->get('to')] ?? null;

        if (is_null($fromRate)) {
            return response()->json(['error' => 'From currency rate not found'], 404);
        }

        if (is_null($toRate)) {
            return response()->json(['error' => 'To currency rate not found'], 404);
        }

        $amount = $request->get('amount');

        $amountInRubles = $amount * $fromRate['rate'];
        $convertedAmount = $amountInRubles / $toRate['rate'];

        return response()->json([
            'converted_amount' => round($convertedAmount, 2),
        ]);
    }
}
