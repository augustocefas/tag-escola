<?php

namespace App\Http\Controllers\Helium;

use App\Models\CentralUser;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Tymon\JWTAuth\Facades\JWTAuth;

class AuthHeliumController extends HeliumController
{
    public function __construct()
    {

    }

    public function showLoginForm()
    {
        return view('helium.auth.login');
    }


    public function isLogged()
    {
        if (auth()->check()) {
            return $this->success(['logged' => true]);
        } else {
            return $this->success(['logged' => false]);
        }
    }
    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required'
        ]);
        $token = auth('api')->attempt($credentials);
        if ($token) {
            if (Auth::attempt($credentials, $request->remember)) {
                $request->session()->regenerate();
                return redirect()->intended('/helium/dashboard');
            }
        }
        return back()->withErrors([
            'email' => 'As credenciais fornecidas não correspondem aos nossos registros.',
        ])->onlyInput('email');
    }
    public function logout(Request $request)
    {
        JWTAuth::invalidate(JWTAuth::getToken());
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/');
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
        if($this->getUserByEmail('dev@localhost.com')) return false;
        $user = new CentralUser();
        $user->name='Dev User';
        $user->email='dev@localhost.com';
        $user->password=Hash::make('@@ldk36n');
        $user->save();
        return $user;
    }
    private function getUserByEmail($email)
    {
        return CentralUser::where('email', $email)->first();
    }
}
