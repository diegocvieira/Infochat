<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Message;
use Auth;
use App\Trabalho;
use App\User;
use DB;
use Mail;
use App\Chat;
use App\BlockedUser;

class MessageController extends Controller
{
    // Enviar mensagem
    public function send(Request $request)
    {
        $user_logged = Auth::guard('web')->user()->id;
        $chat = Chat::where('id', $request->chat_id)
            ->where(function($q) use($user_logged) {
                $q->where('from_id', $user_logged)
                    ->orWhere('to_id', $user_logged);
            })
            ->first();

        if($chat) {
            $user_id = $chat->from_id == $user_logged ? $chat->to_id : $chat->from_id; // User que nao iniciou a conversa

            // Verifica se a conversa esta fechada
            $chat_close = Chat::whereNotNull('close')->find($chat->id);
            // Verifica se o user esta bloqueado
            $user_blocked = BlockedUser::where('user_id', $user_id)
                ->where('blocked_user_id', $user_logged)
                ->first();

            if($chat_close) {
                $return['status'] = 3;
                $return['msg'] = 'Esta conversa foi finalizada.';
            } else if($user_blocked) {
                $return['status'] = 3;
                $return['msg'] = 'Você está impedido de enviar mensagens para este usuário.';
            } else {
                $message = new Message;

                $message->chat_id = $chat->id;
                $message->user_id = $user_logged;
                $message->message = $request->message;
                $message->created_at = date('Y-m-d H:i:s');

                $check = Chat::whereDoesntHave('messages', function($q) use($user_logged) {
                        $q->where('user_id', $user_logged);
                    })
                    ->where('id', $chat->id)
                    ->orWhereHas('messages', function($q) {
                        $q->whereRaw('created_at = (SELECT MAX(created_at) FROM messages) AND TIMESTAMPDIFF(MINUTE, created_at, NOW()) >= 10');
                    })
                    ->where('id', $chat->id)
                    ->select('to_id', 'from_id')
                    ->first();

                if($message->save()) {
                    $return['status'] = 1;

                    if($check) {
                        $email = $check->from_id == $user_logged ? $check->user_to->email : $check->user_from->email;

                        Mail::send('emails.nova_mensagem', [], function($q) use($email) {
                            $q->from('no-reply@infochat.com.br', 'Infochat');
                            $q->to($email)->subject('Nova mensagem');
                        });
                    }
                } else {
                    $return['status'] = 2;
                }
            }
        } else {
            $return['status'] = 3;
            $return['msg'] = 'Ocorreu um erro inesperado. Tente novamente.';
        }

        return json_encode($return);
    }

    // Listar as mensagens do chat
    public function list($id, $offset)
    {
        $chat = Chat::with(['messages' => function($q) use($offset) {
                $q->where('deleted', '!=', Auth::guard('web')->user()->id)
                    ->orWhereNull('deleted')
                    ->offset($offset)
                    ->limit(20)
                    ->orderBy('created_at', 'desc');
            }])->find($id);

        $last_msg = Message::where('chat_id', $id)
            ->select('message')
            ->orderBy('created_at', 'desc')
            ->first();

        // Visualizar as mensagens
        $this->read($chat->id);

        return response()->json([
            'mensagens' => view('inc.list-mensagens-chat', compact('chat'))->render(),
            'last_msg' => isset($last_msg) ? $last_msg->mensagem : ''
        ]);
    }

    // Ler mensagens
    public function read($chat_id)
    {
        Message::whereNull('read_at')
            ->where('chat_id', $chat_id)
            ->where('user_id', '!=', Auth::guard('web')->user()->id)
            ->update(['read_at' => date('Y-m-d H:i:s')]);
    }

    public function newMessages()
    {
        $user_id = Auth::guard('web')->user()->id;

        $return['pessoal'] = Message::whereHas('chat', function($q) use($user_id) {
                $q->where('from_id', $user_id);
            })
            ->where(function($q) use($user_id) {
                $q->where('deleted', '!=', $user_id)
                    ->orWhereNull('deleted');
            })
            ->whereNull('read_at')
            ->where('user_id', '!=', $user_id)
            ->count();

        $return['trabalho'] = Message::whereHas('chat', function($q) use($user_id) {
                $q->where('to_id', $user_id);
            })
            ->where(function($q) use($user_id) {
                $q->where('deleted', '!=', $user_id)
                    ->orWhereNull('deleted');
            })
            ->whereNull('read_at')
            ->where('user_id', '!=', $user_id)
            ->count();

        if($_SERVER['REQUEST_METHOD'] == 'GET') {
            return $return;
        } else {
            return json_encode($return);
        }
    }
}
