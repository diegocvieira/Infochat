<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\PasswordReset;
use App\User;
use App\Trabalho;
use Agent;
use Validator;

class ClaimedController extends Controller
{
    public function createToken($email)
    {
        // Token generate
        $token = hash('sha256', random_bytes(32));

        // Remove previous tokens
        PasswordReset::where('email', $email)->delete();

        // Create a new token
        $pr = new PasswordReset;
        $pr->email = $email;
        $pr->token = $token;
        $pr->created_at = date('Y-m-d H:i:s');
        $pr->save();

        return $token;
    }

    public function checkToken($token)
    {
        $pr = PasswordReset::where('token', $token)->first();

        if($pr) {
            $email = $pr->email;

            $header_title = 'Reivindicar perfil | Infochat';

            if(Agent::isMobile()) {
                return view('mobile.claimed-account', compact('email', 'header_title'));
            } else {
                return view('claimed-account', compact('email', 'header_title'));
            }
        } else {
            return redirect()->route('inicial');
        }
    }

    public function claimedAccount(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'password' => 'confirmed|min:8'
        ], $this->customMessages());

        if($validator->fails()) {
            return redirect()->back()->withErrors($validator);
        } else {
            // Remove o token
            PasswordReset::where('email', $request->email)->delete();

            // Salva o novo password
            $user = User::where('email', $request->email)->firstOrFail();
            $user->password = bcrypt($request->password);
            $user->claimed = 1;
            $user->save();

            // Faz login
            app('App\Http\Controllers\UserController')->login($request);

            return redirect('/');
        }
    }

    public function claimedAccountPhone(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'password' => 'confirmed|min:8',
            'email' => 'max:65|required|email|unique:users'
        ], $this->customMessages());

        if($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        } else {
            $work = Trabalho::where('phone', $request->phone)
                ->whereHas('user', function($query) {
                    $query->where('claimed', 0);
                })
                ->first();

            if(!$work) {
                return redirect()->back()->withErrors('O telefone cadastrado não foi encontrado. Utilize o mesmo número de celular que você recebeu o aviso no whatsapp.')->withInput();
            } else {
                // Salva o novo password
                $user = User::find($work->user_id);
                $user->email = $request->email;
                $user->password = bcrypt($request->password);
                $user->claimed = 1;
                $user->save();

                // Faz login
                app('App\Http\Controllers\UserController')->login($request);

                return redirect('/');
            }
        }
    }

    private function customMessages()
    {
        return [
            'email.max' => 'Seu e-mail deve ter menos de 65 caracteres.',
            'email.required' => 'Precisamos saber o seu e-mail.',
            'email.email' => 'Seu endereço de e-mail é inválido.',
            'email.unique' => 'Este email já está sendo utilizado por outro usuário',
            'password.confirmed' => 'As senhas não conferem',
            'password.min' => 'Sua senha deve ter no mínimo 8 caracteres.',
        ];
    }
}
