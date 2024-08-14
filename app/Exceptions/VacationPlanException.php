<?php

namespace App\Exceptions;

use Exception;

class VacationPlanException extends Exception
{
    protected $statusCode;

    public function __construct(string $message, int $statusCode = 500)
    {
        parent::__construct($message);
        $this->statusCode = $statusCode;
    }

    public function getStatusCode(): int
    {
        return $this->statusCode;
    }

    public static function notFound(): self
    {
        return new self('Vacation plan not found.', 404);
    }

    public static function notCreated(): self
    {
        return new self('Vacation plan not created.', 400);
    }

    public static function notUpdated(): self
    {
        return new self('Vacation plan not updated.', 400);
    }

    public static function notDeleted(): self
    {
        return new self('Vacation plan not deleted.', 500);
    }

    public function render($request)
    {
        return response()->json([
            'error' => $this->message,
        ], $this->statusCode);
    }
}
