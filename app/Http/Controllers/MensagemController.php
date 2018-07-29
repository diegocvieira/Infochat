<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Mensagem;
use Auth;

class MensagemController extends Controller
{
    public function save(Request $request)
    {
        $m = Mensagem::where('remetente_id', 2)
                    ->where('destinatario_id', 1)
                    ->orWhere('remetente_id', 1)
                    ->where('destinatario_id', 2)
                    ->count();

        $mensagem = new Mensagem;

        $mensagem->remetente_id = Auth::guard('web')->user()->id;
        $mensagem->destinatario_id = $request->destinatario_id;
        $mensagem->mensagem = $request->mensagem;
        $mensagem->tipo = $m > 0 ? 0 : 1;

        $mensagem->save();

        return json_encode(['hora' => date('H:i'), 'msg' => $request->mensagem]);
    }

    public function pagination($id, $offset)
    {
        $user_id = Auth::guard('web')->user()->id;

        $mensagens = Mensagem::where('remetente_id', $user_id)
                            ->where('destinatario_id', $id)
                            ->orWhere('remetente_id', $id)
                            ->where('destinatario_id', $user_id)
                            ->offset($offset)
                            ->limit(15)
                            ->orderBy('created_at', 'desc')
                            ->get()
                            ->all();

        return response()->json([
            'mensagens' => view('pagination-mensagens', compact('mensagens'))->render()
        ]);
    }
}
