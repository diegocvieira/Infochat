<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Mensagem;
use Auth;
use App\Trabalho;
use App\User;

class MensagemController extends Controller
{
    // Enviar mensagem
    public function send(Request $request)
    {
        $mensagem = new Mensagem;

        $mensagem->remetente_id = Auth::guard('web')->user()->id;
        $mensagem->destinatario_id = $request->destinatario_id;
        $mensagem->mensagem = $request->mensagem;

        $mensagem->save();

        return json_encode(['hora' => date('H:i'), 'msg' => $request->mensagem]);
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
            ->limit(15)
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
            $mensagens = Mensagem::with('user_destinatario')->whereIn('id', function($query) {
                $query->selectRaw('min(`id`)')
                    ->from('mensagens')
                    ->where('remetente_id', '=', Auth::guard('web')->user()->id)
                    ->groupBy('destinatario_id');
                })
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
            $mensagens = Mensagem::with('user_remetente')->whereIn('id', function($query) {
                $query->selectRaw('min(id)')
                    ->from('mensagens')
                    ->where('destinatario_id', '=', Auth::guard('web')->user()->id)
                    ->groupBy('remetente_id');
                })
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
        $chat = $tipo == 'trabalho' ? Trabalho::find($id) : User::find($id);

        if(Auth::guard('web')->check()) {
            $user_id = Auth::guard('web')->user()->id;
            $id_chat = $tipo == 'trabalho' ? $chat->user_id : $chat->id;

            $mensagens = Mensagem::where('remetente_id', $user_id)
                ->where('destinatario_id', $id_chat)
                ->orWhere('remetente_id', $id_chat)
                ->where('destinatario_id', $user_id)
                ->limit(15)
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
