<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\API\BaseController as BaseController;
use App\Http\Requests\RegisterRequest;
use App\Services\AuthService;
use App\Exceptions\AuthException;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\Response as SymfonyResponse;

class AuthController extends BaseController
{

    public function __construct(protected AuthService $authService)
    {
    }

    public function register(RegisterRequest $request): JsonResponse
    {
        try {
            $data = $request->validated();
            $result = $this->authService->register($data);

            return $this->sendResponse($result, 'User registered successfully.');
        } catch (AuthException $e) {
            Log::error($e->getMessage(), ['auth-exception' => $e]);

            return $this->sendError(
                $e->getMessage(),
                [],
                $e->getStatusCode()
            );
        } catch (\Exception $e) {
            Log::error($e->getMessage(), ['exception' => $e]);

            return $this->sendError(
                'An unexpected error occurred.',
                [],
                SymfonyResponse::HTTP_INTERNAL_SERVER_ERROR
            );
        }
    }

    public function login(Request $request): JsonResponse
    {
        try {
            $credentials = $request->only('email', 'password');
            $result = $this->authService->login($credentials);

            return $this->sendResponse($result, 'User logged in successfully.');
        } catch (AuthException $e) {
            Log::error($e->getMessage(), ['auth-exception' => $e]);

            return $this->sendError(
                $e->getMessage(),
                [],
                $e->getStatusCode()
            );
        } catch (\Exception $e) {
            Log::error($e->getMessage(), ['exception' => $e]);

            return $this->sendError(
                'An unexpected error occurred.',
                [],
                SymfonyResponse::HTTP_INTERNAL_SERVER_ERROR
            );
        }
    }
}
