<?php

namespace App\Exceptions;

use Exception;
use Symfony\Component\HttpFoundation\Response as SymfonyResponse;

class AuthException extends Exception
{
    protected $statusCode;

    public function __construct(string $message, int $statusCode = SymfonyResponse::HTTP_INTERNAL_SERVER_ERROR)
    {
        parent::__construct($message);
        $this->statusCode = $statusCode;
    }

    public function getStatusCode(): int
    {
        return $this->statusCode;
    }

    public static function unauthorized(): self
    {
        return new self('Unauthorized', SymfonyResponse::HTTP_UNAUTHORIZED);
    }

    public static function userNotCreated(): self
    {
        return new self('User not created.', SymfonyResponse::HTTP_BAD_REQUEST);
    }

    public function render($request)
    {
        return response()->json([
            'error' => $this->message,
        ], $this->statusCode);
    }
}
