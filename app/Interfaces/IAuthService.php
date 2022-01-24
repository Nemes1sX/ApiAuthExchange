<?php

namespace App\Interfaces;

use App\Models\User;
use Laravel\Passport\Client as OClient;

interface IAuthService
{
    function getOClient() : OClient;
    function register(string $name, string $email, string $password) : User;
    function login(string $email, string $password) : array;
    function generateToken(string $email, string $password) : array;
    function refreshToken(string $refreshToken) : array;
    function user();
    function sendRequest(string $route,  array $form) : array;
}
