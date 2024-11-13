<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Interfaces\AuthServiceInterface;

class AuthController extends Controller
{
    protected $authService;

    public function __construct(AuthServiceInterface $authService)
    {
        $this->authService = $authService;
    }

    public function loginClient(Request $request)
    {
        $credentials = $request->validate([
            'phone' => 'required',
            'secret_code' => 'required',
        ]);
        return $this->authService->loginClient($credentials);
    }

    public function verifySmsCode(Request $request)
    {
        $data = $request->validate([
            'phone' => 'required',
            'code' => 'required',
        ]);
        return $this->authService->verifySmsCode($data['phone'], $data['code']);
    }

    public function loginDistributeur(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required',
            'password' => 'required',
        ]);
        return $this->authService->loginDistributeur($credentials);
    }

    public function updateFirstLoginCredentials(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'secret_code' => 'required',
            'password' => 'required',
        ]);

        // Transmettez l'e-mail à AuthService
        return $this->authService->updateFirstLoginCredentials($credentials);
    }

    public function refresh(Request $request)
    {
        $refreshToken = $request->validate(['refresh_token' => 'required'])['refresh_token'];
        return $this->authService->refresh($refreshToken);
    }

    public function logout(Request $request)
    {
        return $this->authService->logout($request->bearerToken());
    }
}
