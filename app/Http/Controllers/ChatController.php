<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Chat;
use App\User;
use App\Trabalho;
use Auth;
use App\Message;
use App\BlockedUser;

class ChatController extends Controller
{
    public function show($id, $tipo, $chat_id = null)
    {
        if($tipo == 'trabalho') {
            $destinatario = Trabalho::find($id);

            $destinatario_id = $destinatario->user_id;

            pageview($destinatario->id);
        } else {
            $destinatario = User::find($id);

            $destinatario_id = $destinatario->id;
        }

        if(Auth::guard('web')->check()) {
            $user_id = Auth::guard('web')->user()->id;

            if(!$chat_id) {
                $count = Chat::where('from_id', $user_id)
                    ->where('to_id', $destinatario_id)
                    ->whereNull('close')
                    ->first();

                if(!$count) {
                    $c = new Chat;
                    $c->from_id = $user_id;
                    $c->to_id = $destinatario_id;
                    $c->created_at = date('Y-m-d H:i:s');
                    $c->save();

                    $chat_id = $c->id;
                } else {
                    $chat_id = $count->id;
                }
            }

            $chat = Chat::with(['messages' => function($q) use($user_id) {
                    $q->where('deleted', '!=', $user_id)
                        ->orWhereNull('deleted')
                        ->limit(20)
                        ->orderBy('created_at', 'desc');
                }])->find($chat_id);

            // Visualizar as mensagens
            app('App\Http\Controllers\MessageController')->read($chat_id);

            // Count mensagens aba trabalho/pessoal
            $new_messages = app('App\Http\Controllers\MessageController')->newMessages();
            $new_messages_pessoal = $new_messages['pessoal'];
            $new_messages_trabalho = $new_messages['trabalho'];
        } else {
            $new_messages_trabalho = null;
            $new_messages_pessoal = null;
        }

        return response()->json([
            'trabalho' => view('inc.chat', compact('destinatario', 'chat', 'tipo'))->render(),
            'new_messages_trabalho' => $new_messages_trabalho,
            'new_messages_pessoal' => $new_messages_pessoal
        ]);
    }

    // Listar os ultimos trabalhos que enviaram uma mensagem
    public function pessoal()
    {
        if(Auth::guard('web')->check()) {
            $user_id = Auth::guard('web')->user()->id;

            $chats = Chat::where('from_id', $user_id)
                ->whereHas('messages', function($q) use($user_id) {
                    $q->where('deleted', '!=', $user_id)
                        ->orWhereNull('deleted');
                })
                ->orderBy('created_at', 'desc')
                ->get();
        }

        $section = 'pessoal';

        return response()->json([
            'mensagens' => view('inc.list-mensagens-pessoal', compact('chats', 'section'))->render()
        ]);
    }

    // Listar os ultimos usuarios que enviaram uma mensagem
    public function trabalho()
    {
        if(Auth::guard('web')->check()) {
            $user_id = Auth::guard('web')->user()->id;

            $chats = Chat::where('to_id', $user_id)
                ->whereHas('messages', function($q) use($user_id) {
                    $q->where('deleted', '!=', $user_id)
                        ->orWhereNull('deleted');
                })
                ->orderBy('created_at', 'desc')
                ->get();
        }

        $section = 'trabalho';

        return response()->json([
            'mensagens' => view('inc.list-mensagens-trabalho', compact('chats', 'section'))->render()
        ]);
    }

    public function close($id)
    {
        $chat = Chat::find($id);

        $chat->close = Auth::guard('web')->user()->id;

        if($chat->save()) {
            $return['status'] = true;
            $return['route'] = route('open-chat', $id);
        } else {
            $return['status'] = false;
        }

        return json_encode($return);
    }

    public function open($id)
    {
        $chat = Chat::find($id);

        $chat->close = null;

        if($chat->save()) {
            $return['status'] = true;
            $return['route'] = route('close-chat', $id);
        } else {
            $return['status'] = false;
        }

        return json_encode($return);
    }

    public function delete($id)
    {
        $user_id = Auth::guard('web')->user()->id;

        $chat = Chat::where('id', $id)
            ->where(function($q) use($user_id) {
                $q->where('from_id', $user_id)
                    ->orWhere('to_id', $user_id);
            })
            ->first();

        $chat->messages()->whereNotNull('deleted')->delete();
        $chat->messages()->whereNull('deleted')->update(['deleted' => $user_id]);

        $return['status'] = true;

        return json_encode($return);
    }

    public function blockUser($id)
    {
        $block = new BlockedUser;

        $block->user_id = Auth::guard('web')->user()->id;
        $block->blocked_user_id = $id;

        if($block->save()) {
            $return['status'] = true;
            $return['route'] = route('unblock-user', $id);
        } else {
            $return['status'] = false;
        }

        return json_encode($return);
    }

    public function unblockUser($id)
    {
        $block = BlockedUser::where('user_id', Auth::guard('web')->user()->id)
            ->where('blocked_user_id', $id)
            ->first();

        if($block->delete()) {
            $return['status'] = true;
            $return['route'] = route('block-user', $id);
        } else {
            $return['status'] = false;
        }

        return json_encode($return);
    }
}
