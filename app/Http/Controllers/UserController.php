<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;
use Validator;
use Session;
use Auth;

class UserController extends Controller
{
    public function create(Request $request)
    {
        $dataForm = $request->all();

        $dataForm['password'] = bcrypt($dataForm['password']);

        $validator = Validator::make($request->all(), [
            'email' => 'unique:users'
        ]);

        if($validator->fails()) {
            $return = ['status' => false, 'msg' => 'Este e-mail já está sendo utilizado por outro usuário.'];
        } else {
            if(User::create($dataForm)) {
                return $this->login($request);
            } else {
                $return = ['status' => false, 'msg' => 'Ocorreu um erro. Por favor, tente novamente.'];
            }
        }

        return json_encode($return);
    }

    public function login(Request $request)
    {
        if(Auth::guard('web')->attempt(['email' => $request->email, 'password' => $request->password])) {
            $return = ['status' => true];
        } else {
            $return = ['status' => false, 'msg' => 'Não identificamos o e-mail e/ou a senha que você informou.'];
        }

        return json_encode($return);
    }

    public function logout()
    {
        Session::flush();
        Auth::logout();

        return redirect('/');
    }
}
