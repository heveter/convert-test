<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CurrencyResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'code' => $this->resource['currency'],
            'name' => $this->resource['name'],
            'rate' => $this->resource['rate']
        ];
    }
}
