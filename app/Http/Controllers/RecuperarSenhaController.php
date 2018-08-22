<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\PasswordReset;
use Mail;
use Validator;
use App\User;
use Agent;

class RecuperarSenhaController extends Controller
{
    public function solicitar(Request $request)
    {
        $email = $request->email;

        $count = User::where('email', $email)->count();

        if($count) {
            $return['status'] = true;

            // Gera o token
            $token = hash('sha256', random_bytes(32));

            // Remove tokens anteriores
            PasswordReset::where('email', $email)->delete();

            // Cria o novo token
            $pr = new PasswordReset;
            $pr->email = $email;
            $pr->token = $token;
            $pr->created_at = date('Y-m-d H:i:s');
            $pr->save();

            $url = url('/') . '/recuperar-senha/check/' . $token;

            Mail::send('emails.recuperar-senha', ['url' => $url], function($q) use($email) {
                $q->from('no-reply@infochat.com.br', 'Infochat');
                $q->to($email)->subject('Recuperar senha');
            });
        } else {
            $return['status'] = false;
        }

        return json_encode($return);
    }

    public function check($token)
    {
        $pr = PasswordReset::where('token', $token)->firstOrFail();

        $email = $pr->email;

        $header_title = 'Recuperar senha | Infochat';

        if(Agent::isMobile()) {
            return view('mobile.recuperar-senha', compact('email', 'header_title'));
        } else {
            return view('recuperar-senha', compact('email', 'header_title'));
        }
    }

    public function alterar(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'password' => 'confirmed|min:8'
        ], [
            'password.confirmed' => 'As senhas não conferem',
            'password.min' => 'A senha deve ter no mínimo 8 caracteres'
        ]);

        if($validator->fails()) {
            return redirect()->back()->withErrors($validator);
        } else {
            // Remove o token
            PasswordReset::where('email', $request->email)->delete();

            // Salva o novo password
            $user = User::where('email', $request->email)->firstOrFail();
            $user->password = bcrypt($request->password);
            $user->save();

            // Faz login
            app('App\Http\Controllers\UserController')->login($request);

            return redirect('/');
        }
    }
}
