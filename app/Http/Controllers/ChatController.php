<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Chat;
use App\User;
use App\Trabalho;
use Auth;
use App\Message;
use App\BlockedUser;
use Cookie;
use Agent;

class ChatController extends Controller
{
    public function show($id, $tipo, $chat_id = null)
    {
        if($tipo == 'trabalho') {
            $destinatario = Trabalho::find($id);

            $destinatario_id = $destinatario->user_id;

            pageview($destinatario->id);

            $destinatario_slug = $destinatario->slug;
        } else {
            $destinatario = User::find($id);

            $destinatario_id = $destinatario->id;

            $destinatario_slug = null;
        }

        if(Auth::guard('web')->check()) {
            if($chat_id) {
                $messages = app('App\Http\Controllers\MessageController')->list($chat_id, 1);

                // Visualizar as mensagens
                app('App\Http\Controllers\MessageController')->read($chat_id);
            }

            // Count mensagens aba trabalho/pessoal
            $new_messages = app('App\Http\Controllers\MessageController')->newMessages();
            $new_messages_pessoal = $new_messages['pessoal'];
            $new_messages_trabalho = $new_messages['trabalho'];
        } else {
            $new_messages_trabalho = null;
            $new_messages_pessoal = null;
        }

        if(Agent::isMobile()) {
            return response()->json([
                'trabalho' => view('mobile.inc.chat', compact('destinatario', 'tipo', 'chat_id', 'destinatario_id', 'messages'))->render(),
                'new_messages_trabalho' => $new_messages_trabalho,
                'new_messages_pessoal' => $new_messages_pessoal,
                'destinatario_slug' => $destinatario_slug
            ]);
        } else {
            return response()->json([
                'trabalho' => view('inc.chat', compact('destinatario', 'tipo', 'chat_id', 'destinatario_id', 'messages'))->render(),
                'new_messages_trabalho' => $new_messages_trabalho,
                'new_messages_pessoal' => $new_messages_pessoal,
                'destinatario_slug' => $destinatario_slug
            ]);
        }
    }

    public function showChatUrl($slug)
    {
        $trabalhos = Trabalho::filtroStatus()->where('slug', $slug)->paginate(1);

        if(count($trabalhos) > 0) {
            if(Cookie::get('sessao_cidade_id') != $trabalhos->first()->cidade_id || Cookie::get('sessao_estado_letter_lc') != $trabalhos->first()->cidade->estado->letter_lc) {
                _setCidade($trabalhos->first()->cidade, $force = true);

                return redirect(action('ChatController@showChatUrl', $slug));
            }

            // SEO
            $header_title = $trabalhos->first()->nome . ' - ' . Cookie::get('sessao_cidade_title') . '/' . Cookie::get('sessao_estado_letter') . ' | Infochat';
            $header_desc = 'Clique para ver o perfil de ' . $trabalhos->first()->nome . ' em ' . Cookie::get('sessao_cidade_title') . '/' . Cookie::get('sessao_estado_letter') . ' no site infochat.com.br';

            $destinatario = $trabalhos->first();
            $palavra_chave = $trabalhos->first()->nome;
            $chat_id = null;
            $tipo = 'trabalho';
            $destinatario_id = $trabalhos->first()->user_id;

            pageview($trabalhos->first()->id);

            if(Auth::guard('web')->check()) {
                $logged_user = Auth::guard('web')->user()->id;

                if(!$chat_id) {
                    $count = Chat::where('from_id', $logged_user)
                        ->where('to_id', $destinatario_id)
                        ->whereNull('close')
                        ->first();

                    if(!$count) {
                        $c = new Chat;
                        $c->from_id = $logged_user;
                        $c->to_id = $destinatario_id;
                        $c->created_at = date('Y-m-d H:i:s');
                        $c->save();

                        $chat_id = $c->id;
                    } else {
                        $chat_id = $count->id;
                    }
                }

                $messages = app('App\Http\Controllers\MessageController')->list($chat_id, 1);

                // Visualizar as mensagens
                app('App\Http\Controllers\MessageController')->read($chat_id);
            }

            if(Agent::isMobile()) {
                return view('mobile.show-chat-url', compact('palavra_chave', 'chat_id', 'trabalhos', 'messages', 'tipo', 'destinatario', 'destinatario_id', 'header_desc', 'header_title'));
            } else {
                return view('show-chat-url', compact('palavra_chave', 'chat_id', 'trabalhos', 'messages', 'tipo', 'destinatario', 'destinatario_id', 'header_desc', 'header_title'));
            }
        } else {
            return view('errors.404');
        }
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

        if(Agent::isMobile()) {
            return response()->json([
                'mensagens' => view('mobile.inc.list-mensagens-pessoal', compact('chats', 'section'))->render()
            ]);
        } else {
            return response()->json([
                'mensagens' => view('inc.list-mensagens-pessoal', compact('chats', 'section'))->render()
            ]);
        }
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

        if(Agent::isMobile()) {
            return response()->json([
                'mensagens' => view('mobile.inc.list-mensagens-trabalho', compact('chats', 'section'))->render()
            ]);
        } else {
            return response()->json([
                'mensagens' => view('inc.list-mensagens-trabalho', compact('chats', 'section'))->render()
            ]);
        }
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
