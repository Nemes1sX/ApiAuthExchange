<?php

namespace App\Services;

use App\Http\Requests\LoginRequest;
use App\Interfaces\IAuthService;
use App\Models\User;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\ClientException;
use Laravel\Passport\Client as OClient;

class AuthService implements IAuthService
{
    const ROOT_URL = 'localhost:8001';

    public function __construct(Client $client) {
        $this->http = $client;
    }

    public function register(string $name, string $email, string $password) : User {
       $user = User::create([
            'name' => $name,
            'email' => $email,
            'password' => $password
        ]);

        return $user;
    }

    public function login(string $email, string $password) : array {
        if (auth()->attempt(['email' => $email, 'password' => $password])) {
            return $this->generateToken($email, $password);
        } else {
            return ['response' => "Invalid credentials", 'status_code' => 401];
        }
    }

    public function generateToken(string $email, string $password) : array
    {
        $oClient = $this->getOClient();
        $form =  [
            'grant_type' => 'password',
            'client_id' => $oClient->id,
            'client_secret' => $oClient->secret,
            'username' => $email,
            'password' => $password,
            'scope' => '*'
        ];

        return $this->sendRequest('/oauth/token', $form);
    }

    public function refreshToken(string $refreshToken) : array
    {
        $oClient = $this->getOClient();
        $form =  [
            'grant_type' => 'refresh_token',
            'refresh_token' => $refreshToken,
            'client_id' => $oClient->id,
            'client_secret' => $oClient->secret,
            'scope' => '*'
        ];

        return $this->sendRequest('/oauth/token', $form);
    }

    public function user() {
        return auth()->user();
    }

    public function getOClient() : OClient {
        return OClient::where('password_client', 1)->first();
    }

    public function sendRequest(string $route, array $form) : array {
        try {
          $url = self::ROOT_URL.$route;
          $response = $this->http->request('POST', $url, ['form_params' => $form]);
          $statusCode = 200;
          $data = json_decode((string) $response->getBody(), true);
        } catch (ClientException $ex) {
            $statusCode = $ex->getCode();
            $data = ['error' => 'OAuth error'];
        }

        return ['response' => $data, 'status_code' => $statusCode];
    }
}
