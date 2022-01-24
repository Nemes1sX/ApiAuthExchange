<?php

namespace App\Http\Controllers;

use App\Http\Requests\LoginRequest;
use App\Http\Requests\RegisterRequest;
use App\Interfaces\IAuthService;
use App\Interfaces\IExchangeService;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;

class UserController extends Controller
{
    public function __construct(IAuthService $authService, IExchangeService $exchangeService)
    {
        $this->authService = $authService;
        $this->exchangeService = $exchangeService;
    }

    public function register(RegisterRequest $request)
    {
       $user = $this->authService->register($request->name, $request->email, $request->password);

        return response()->json([
            'status' => 'User was created',
            'user' => $user
        ], 200);
    }

    public function login(LoginRequest $request)
    {
        $data = $this->authService->login($request->email, $request->password);

        return response()->json([$data['response']], $data['status_code']);
    }

    public function exchangeCurrency(string $from, string $to)
    {
        $data = $this->exchangeService->exchangeCurrency($from, $to);

        return response()->json(['data' => $data['data']], $data['status_code']);
    }

    public function details()
    {
        $data = $this->authService->user();

        return response()->json(['user' => $data], 200);
    }

    public function refresh(Request $request) {
        if (is_null($request->header('Refresh-Token'))) {
            return response()->json(['error' => 'Refresh token is empty'], 400);
        }

        $data = $this->authService->refreshToken($request->header('Refresh-Token'));

        return response()->json([$data['response']], $data['status_code']);
    }

    public function logout(Request $request)
    {
        $request->user()->token()->revoke();

        return response()->json([
            'message' => 'You successfully logout'
        ], 200);
    }
}
