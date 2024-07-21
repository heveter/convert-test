<?php

namespace App\Exceptions;

use Illuminate\Auth\AuthenticationException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Throwable;

class Handler extends ExceptionHandler
{
    /**
     * The list of the inputs that are never flashed to the session on validation exceptions.
     *
     * @var array<int, string>
     */
    protected $dontFlash = [
        'current_password',
        'password',
        'password_confirmation',
    ];

    /**
     * Register the exception handling callbacks for the application.
     */
    public function register(): void
    {
        $this->renderable(function (BaseMessageException $e) {
            return response()->json(['message' => $e->getMessage()], 400);
        });
    }

    protected function unauthenticated($request, AuthenticationException $exception)
    {
        return response()->json(['message' => 'unauthenticated'], 401);
    }
}
