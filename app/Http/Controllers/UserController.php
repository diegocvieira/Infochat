<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;
use Validator;
use Session;
use Auth;
use Hash;
use Agent;

class UserController extends Controller
{
    public function create(Request $request)
    {
        $validator = Validator::make($request->all(), $this->userCreateRules(), $this->customMessages());

        if($validator->fails()) {
            $return['msg'] = $validator->errors()->first();
            $return['status'] = false;
        } else {
            $usuario = new User;

            $usuario->password = bcrypt($request->password);
            $usuario->nome = $request->nome;
            $usuario->email = $request->email;

            if($usuario->save()) {
                return $this->login($request);
            } else {
                $return['msg'] = 'Ocorreu um erro inesperado. Tente novamente.';
                $return['status'] = false;
            }
        }

        return json_encode($return);
    }

    public function login(Request $request)
    {
        if(Auth::guard('web')->attempt(['email' => $request->email, 'password' => $request->password])) {
            $user = User::find(Auth::guard('web')->user()->id);
            $user->online = 1;
            $user->save();

            $return['status'] = true;
            $return['id'] = $user->id;
        } else {
            $return['status'] = false;
            $return['msg'] = 'Não identificamos o e-mail e/ou a senha que você informou.';
        }

        return json_encode($return);
    }

    public function logout()
    {
        $user = User::find(Auth::guard('web')->user()->id);
        $user->online = 0;
        $user->save();

        Session::flush();
        Auth::logout();

        return redirect('/');
    }

    public function getConfig()
    {
        $usuario = User::find(Auth::guard('web')->user()->id);

        if(Agent::isMobile()) {
            return response()->json([
                'body' => view('mobile.admin.usuario-config', compact('usuario'))->render()
            ]);
        } else {
            return response()->json([
                'body' => view('admin.usuario-config', compact('usuario'))->render()
            ]);
        }
    }

    public function setConfig(Request $request)
    {
        $validator = Validator::make($request->all(), $this->userUpdateRules(), $this->customMessages());

         if($validator->fails()) {
             $return['msg'] = $validator->errors()->first();
             $return['status'] = 0;
        } else {
            if(Hash::check($request->senha_atual, Auth::guard('web')->user()->password)) {
                $usuario = User::find(Auth::guard('web')->user()->id);

                $usuario->nome = $request->nome;
                $usuario->email = $request->email;

                if($request->password) {
                    $usuario->password = bcrypt($request->password);
                }

                if(!empty($request->img)) {
                    $usuario->imagem = _uploadImage($request->img, $usuario->imagem);
                }

                if($usuario->save()) {
                    $return['msg'] = 'Informações atualizadas.';
                    $return['status'] = 1;
                } else {
                    $return['msg'] = 'Ocorreu um erro inesperado. Tente novamente.';
                    $return['status'] = 0;
                }
            } else {
                $return['msg'] = 'A sua senha atual não confere.';
                $return['status'] = 2;
            }
        }

        return json_encode($return);
    }

    public function excluirConta(Request $request)
    {
        if(Hash::check($request->password, Auth::guard('web')->user()->password)) {
            $usuario = User::find(Auth::guard('web')->user()->id);

            if($usuario->imagem) {
                unlink('uploads/perfil/' . $usuario->imagem);
            }

            $usuario->delete();

            Session::flush();
            Auth::logout();

            $return['status'] = true;
        } else {
            $return['status'] = false;
        }

        return json_encode($return);
    }

    public function tokenOnesignal(Request $request)
    {
        $user = User::findOrFail($request->id);
        $user->onesignal_token = $request->token;
        $user->save();
    }

    private function userUpdateRules()
    {
        return [
            'email' => 'required|email|max:65|unique:users,email,' . Auth::guard('web')->user()->id,
            'nome' => 'required|max:100',
            'img' => 'image|max:5000',
            'password' => 'confirmed'
        ];
    }

    private function userCreateRules()
    {
        return [
            'email' => 'required|email|max:65|unique:users',
            'nome' => 'required|max:100',
            'password' => 'confirmed|min:8'
        ];
    }

    private function customMessages()
    {
        return [
            'nome.required' => 'Precisamos saber o seu nome.',
            'nome.max' => 'Seu nome deve ter menos de 100 caracteres.',
            'email.max' => 'Seu e-mail deve ter menos de 65 caracteres.',
            'email.required' => 'Precisamos saber o seu e-mail.',
            'email.email' => 'Seu endereço de e-mail é inválido.',
            'email.unique' => 'Este email já está sendo utilizado por outro usuário',
            'img.image' => 'Imagem inválida',
            'password.confirmed' => 'As senhas não conferem',
            'password.min' => 'Sua senha deve ter no mínimo 8 caracteres.',
            'img.max' => 'A imagem tem que ter no máximo 5mb.'
        ];
    }
}
