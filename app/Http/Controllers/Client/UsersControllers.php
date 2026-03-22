<?php

namespace App\Http\Controllers\Client;


use App\Models\Client\Usuarios;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;


class UsersControllers extends ClientController
{
    public function __construct()
    {
        parent::__construct();
    }

    public function index(){
        $users = Usuarios::with('anexo')->get();
        return $this->success($users);
    }
    public function store(Request $request){
        $data = request()->validate([
            'name' => 'required|string',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:6',
        ]);

        $user = User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => bcrypt($data['password']),
            'change_pass' => true,
            'is_gestor'  => $request->is_gestor ? true : false,
        ]);

        return $this->success($user, 201);
    }

  
    public function setDarkMode(string $dark_mode){
        $user = auth()->user();
        $user->dark_mode = $dark_mode === 'true' ? true : false;
        $user->save();
        return $this->success($user);
    }

    public function update(Request $request, $id){
        $user = User::findOrFail($id);
        $data = request()->validate([
            'name' => 'sometimes|required|string',
            'email' => 'sometimes|required|email|unique:users,email,'.$user->id,
            'password' => 'sometimes|required|string|min:6',
        ]);

        if (isset($data['name'])) {
            $user->name = $data['name'];
        }
        if (isset($data['email'])) {
            $user->email = $data['email'];
        }

        $user->is_gestor = $request->is_gestor  ? true : false;

        if (isset($data['password'])) {
            $user->password = bcrypt($data['password']);
            $user->change_pass = true;
        }

        $user->save();

        return $this->success($user);
    }

    public function destroy($id){
        $user = User::findOrFail($id);
        $user->delete();
        return $this->success(['message' => 'Usuario deletado com sucesso']);
    }

    public function changePassword(Request $request){
        $data = $request->validate([
            'senha_atual' => 'required|string',
            'nova_senha' => 'required|string|min:6',
            'confirmacao_nova_senha' => 'required|string|same:nova_senha',
        ], [
            'senha_atual.required' => 'A senha atual é obrigatória',
            'nova_senha.required' => 'A nova senha é obrigatória',
            'nova_senha.min' => 'A nova senha deve ter no mínimo 6 caracteres',
            'confirmacao_nova_senha.required' => 'A confirmação da nova senha é obrigatória',
            'confirmacao_nova_senha.same' => 'A confirmação da senha não coincide com a nova senha',
        ]);

        $user = Usuarios::findOrFail(auth()->id());

        // Verifica se a senha atual está correta
        if (!Hash::check($data['senha_atual'], $user->password)) {
            return $this->error('A senha atual está incorreta', 422);
        }

        // Atualiza a senha
        $user->password = Hash::make($data['nova_senha']);
        $user->change_pass = false; // Remove flag de trocar senha
        $user->save();

        return $this->success(['message' => 'Senha alterada com sucesso']);
    }
}
