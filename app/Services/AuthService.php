<?php

namespace App\Services;

use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use App\Exceptions\AuthException;
use App\Repositories\AuthRepository;

class AuthService
{
    public function __construct(protected AuthRepository $authRepository)
    {
    }

    public function register(array $data)
    {
        $data['password'] = Hash::make($data['password']);
        $user = $this->authRepository->create($data);

        if (!$user) {
            throw AuthException::userNotCreated();
        }

        $token = $user->createToken('MyApp')->plainTextToken;

        return [
            'token' => $token,
            'name' => $user->name,
        ];
    }

    public function login(array $credentials)
    {
        if (Auth::attempt($credentials)) {
            $user = Auth::user();
            $token = $user->createToken('MyApp')->plainTextToken;

            return [
                'token' => $token,
                'name' => $user->name,
            ];
        }

        throw AuthException::unauthorized();
    }
}
