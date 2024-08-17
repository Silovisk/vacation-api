<?php

namespace App\Exceptions;

use Exception;
use Symfony\Component\HttpFoundation\Response as SymfonyResponse;

class VacationPlanException extends Exception
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

    public static function noDataAvailable()
    {
        return new self("No vacation plan data available.", SymfonyResponse::HTTP_NOT_FOUND);
    }

    public static function notFound(): self
    {
        return new self('Vacation plan not found.', SymfonyResponse::HTTP_NOT_FOUND);
    }

    public static function notCreated(): self
    {
        return new self('Vacation plan not created.', SymfonyResponse::HTTP_BAD_REQUEST);
    }

    public static function notUpdated(): self
    {
        return new self('Vacation plan not updated.', SymfonyResponse::HTTP_BAD_REQUEST);
    }

    public static function notDeleted(): self
    {
        return new self('Vacation plan not deleted.', SymfonyResponse::HTTP_BAD_REQUEST);
    }

    public function render($request)
    {
        return response()->json([
            'error' => $this->message,
        ], $this->statusCode);
    }
}
