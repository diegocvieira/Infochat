<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Mensagem;
use Auth;
use App\Trabalho;
use App\User;
use DB;
use Mail;

class MensagemController extends Controller
{
    // Enviar mensagem
    public function send(Request $request)
    {
        $remetente_id = Auth::guard('web')->user()->id;
        $destinatario_id = $request->destinatario_id;

        if($remetente_id != $destinatario_id) {
            $mensagem = new Mensagem;

            $mensagem->remetente_id = $remetente_id;
            $mensagem->destinatario_id = $destinatario_id;
            $mensagem->mensagem = $request->mensagem;

            if($mensagem->save()) {
                $count = Mensagem::selectRaw('SUM(CASE WHEN NOT EXISTS (SELECT id FROM mensagens WHERE remetente_id = ' . $remetente_id . ' AND destinatario_id = ' . $destinatario_id . ') THEN 1 ELSE 0 END) AS result,
                    SUM(CASE WHEN created_at = (SELECT MAX(created_at) FROM mensagens WHERE remetente_id = ' . $remetente_id . ' AND destinatario_id = ' . $destinatario_id . ')
                    AND remetente_id = ' . $remetente_id . ' AND destinatario_id = ' . $destinatario_id . '
                    AND TIMESTAMPDIFF(MINUTE, created_at, NOW()) >= 10 THEN 1 ELSE 0 END) AS result2')
                    ->first();

                if(count($count->result) > 0 || count($count->result2) > 0) {
                    $user = User::select('email')->find($destinatario_id);

                    Mail::send('emails.nova_mensagem', [], function($q) use($user) {
                        $q->from('no-reply@infochat.com.br', 'Infochat');
                        $q->to($user->email)->subject('Nova mensagem');
                    });
                }
            }

            return json_encode(['hora' => date('H:i'), 'msg' => $request->mensagem]);
        }
    }

    // Listar as mensagens do chat
    public function list($id, $offset)
    {
        $user_id = Auth::guard('web')->user()->id;

        $mensagens = Mensagem::where('remetente_id', $user_id)
            ->where('destinatario_id', $id)
            ->orWhere('remetente_id', $id)
            ->where('destinatario_id', $user_id)
            ->offset($offset)
            ->limit(20)
            ->orderBy('created_at', 'desc')
            ->get()
            ->all();

        $last_msg = Mensagem::where('remetente_id', $id)
            ->where('destinatario_id', $user_id)
            ->select('mensagem')
            ->orderBy('created_at', 'desc')
            ->first();

        return response()->json([
            'mensagens' => view('inc.list-mensagens-chat', compact('mensagens'))->render(),
            'last_msg' => isset($last_msg) ? $last_msg->mensagem : ''
        ]);
    }

    // Listar os ultimos trabalhos que enviaram uma mensagem
    public function pessoal()
    {
        if(Auth::guard('web')->check()) {
            $mensagens = Mensagem::with('user_destinatario')
                ->where('remetente_id', Auth::guard('web')->user()->id)
                ->where('created_at', '<=', function($q) {
                    $q->from('mensagens AS m2')
                      ->select('created_at')
                      ->whereColumn('m2.destinatario_id', '=', 'mensagens.remetente_id')
                      ->whereColumn('m2.remetente_id', '=', 'mensagens.destinatario_id')
                      ->OrWhereColumn('m2.destinatario_id', '=', 'mensagens.destinatario_id')
                      ->whereColumn('m2.remetente_id', '=', 'mensagens.remetente_id')
                      ->limit(1);
                })
                ->select('destinatario_id', 'created_at')
                ->groupBy('destinatario_id', 'created_at')
                ->orderBy('created_at', 'desc')
                ->get();
        }

        $section = 'pessoal';

        return response()->json([
            'mensagens' => view('inc.list-mensagens-pessoal', compact('mensagens', 'section'))->render()
        ]);
    }

    // Listar os ultimos usuarios que enviaram uma mensagem
    public function trabalho()
    {
        if(Auth::guard('web')->check()) {
            $mensagens = Mensagem::with('user_remetente')
                ->where('destinatario_id', Auth::guard('web')->user()->id)
                ->where('created_at', '<=', function($q) {
                    $q->from('mensagens AS m2')
                      ->select('created_at')
                      ->whereColumn('m2.remetente_id', '=', 'mensagens.destinatario_id')
                      ->whereColumn('m2.destinatario_id', '=', 'mensagens.remetente_id')
                      ->OrWhereColumn('m2.remetente_id', '=', 'mensagens.remetente_id')
                      ->whereColumn('m2.destinatario_id', '=', 'mensagens.destinatario_id')
                      ->limit(1);
                })
                ->select('remetente_id', 'created_at')
                ->groupBy('remetente_id', 'created_at')
                ->orderBy('created_at', 'desc')
                ->get();
        }

        $section = 'trabalho';

        return response()->json([
            'mensagens' => view('inc.list-mensagens-trabalho', compact('mensagens', 'section'))->render()
        ]);
    }

    public function chat($id, $tipo)
    {
        if($tipo == 'trabalho') {
            $chat = Trabalho::find($id);

            pageview($chat->id);
        } else {
            $chat = User::find($id);
        }

        if(Auth::guard('web')->check()) {
            $user_id = Auth::guard('web')->user()->id;
            $id_chat = $tipo == 'trabalho' ? $chat->user_id : $chat->id;

            $mensagens = Mensagem::where('remetente_id', $user_id)
                ->where('destinatario_id', $id_chat)
                ->orWhere('remetente_id', $id_chat)
                ->where('destinatario_id', $user_id)
                ->limit(20)
                ->orderBy('created_at', 'desc')
                ->get()
                ->all();

            // Visualizar as mensagens
            Mensagem::whereNull('lida')
                ->where('remetente_id', $id_chat)
                ->where('destinatario_id', $user_id)
                ->update(['lida' => date('Y-m-d H:i:s')]);
        }

        return response()->json([
            'trabalho' => view('inc.chat', compact('chat', 'avaliacao', 'mensagens', 'tipo'))->render()
        ]);
    }
}
