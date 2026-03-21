<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Support\Facades\Hash;

class AuthController extends BasicTenantController
{
    public function __construct()
    {

    }

    public function isLogged()
    {
        if (auth()->check()) {
            return $this->success(['logged' => true]);
        } else {
            return $this->success(['logged' => false]);
        }
    }
    public function login()
    {
        $credentials = request(['email', 'password']);
        if (! $token = auth()->attempt($credentials)) {
            return $this->error('Dados incorretos', 401);
        }
        return $this->success($this->respondWithToken($token));
    }
    public function logout()
    {
        auth()->logout();
        return $this->success(['message' => 'Successfully logged out']);
    }
    public function refresh()
    {
        return $this->success($this->respondWithToken(auth()->refresh()));
    }
    public function me()
    {
        return $this->success(auth()->user());
    }

    protected function respondWithToken($token)
    {
        //getTTL atualmente rotorna 60 minutos
        return [
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => auth()->factory()->getTTL() * 60
        ];
    }

    public function dev(){
        $user = User::create([
            'name' => 'Dev User',
            'email' => 'dev@localhost.com',
            'password' => Hash::make('@@ldk36n')
        ]);

        return $this->success($user);

    }
}
