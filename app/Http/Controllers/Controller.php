<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;
use OpenApi\Attributes;

#[Attributes\Info(
    version: '1',
    title: 'Swagger'
)]

#[Attributes\SecurityScheme(
    securityScheme: 'AuthHeader',
    type: 'http',
    in: 'header',
    scheme: 'bearer'
)]
class Controller extends BaseController
{
    use AuthorizesRequests, ValidatesRequests;
}
