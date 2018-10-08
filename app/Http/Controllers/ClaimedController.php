<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\PasswordReset;
use App\User;
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
        $pr = PasswordReset::where('token', $token)->firstOrFail();

        $email = $pr->email;

        $header_title = 'Reivindicar conta | Infochat';

        if(Agent::isMobile()) {
            return view('mobile.claimed-account', compact('email', 'header_title'));
        } else {
            return view('claimed-account', compact('email', 'header_title'));
        }
    }

    public function claimedAccount(Request $request)
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
            $user->claimed = 1;
            $user->save();

            // Faz login
            app('App\Http\Controllers\UserController')->login($request);

            return redirect('/');
        }
    }
}
