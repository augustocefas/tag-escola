<?php

    namespace App\Http\Controllers\Client;

    use App\Models\Client\Usuarios;
    use App\Models\User;
    use Illuminate\Support\Facades\Hash;

    class AuthController extends AController{
        public function __construct(){
            parent::__construct();
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
            $user = auth()->user();
            $me = Usuarios::where('id', $user->id)->with('anexo')->first();
            return $this->success($me);
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

        public function devCreateUser(){
            try{
                $user = User::create([
                    'name' => 'SUPORTE',
                    'email' => 'client@localhost.com',
                    'password' => Hash::make('@@ldk36n')
                ]);
            }catch (\Exception $e){
                return $this->error('User already exists');
            }
            return $this->success('Usuario de suporte criado com sucesso');
        }
        public function devDeleteUser(){
            try{
                $user = User::query()->where('email', 'client@localhost.com');
                if($user->exists()){
                    $user->delete();
                    return $this->success('User deleted');
                }else{
                    return $this->error('User not found');
                }
            }catch (\Exception $e){
                return $this->error('Error deleting user');
            }
        }
    }
