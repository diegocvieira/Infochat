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
use DB;

class ChatController extends Controller
{
    public function show($id, $tipo, $chat_id = null)
    {
        if(!Auth::guard('web')->check() && $chat_id) {
            return redirect()->route('user-login');
        }

        if(Auth::guard('web')->check()) {
            $logged_user = Auth::guard('web')->user()->id;
        }

        if($tipo == 'trabalho') {
            $destinatario = Trabalho::find($id);

            $destinatario_id = $destinatario->user_id;
            $destinatario_slug = $destinatario->slug;

            pageview($destinatario->id);

            if(Auth::guard('web')->check() && !$chat_id) {
                $check_chat = Chat::whereNull('close')
                    ->whereHas('messages', function($query) use($logged_user) {
                        $query->where('deleted', '!=', $logged_user)
                            ->orWhereNull('deleted');
                    })
                    ->where('from_id', $logged_user)
                    ->where('to_id', $destinatario_id)
                    ->first();

                if($check_chat) {
                    $chat_id = $check_chat->id;
                }
            }
        } else {
            $destinatario = User::find($id);

            $destinatario_id = $destinatario->id;
            $destinatario_slug = null;
        }

        if(Auth::guard('web')->check()) {
            if($chat_id) {
                $chat_validate = Chat::where('id', $chat_id)
                    ->where(function($query) use($logged_user) {
                        $query->where('from_id', $logged_user)
                            ->orWhere('to_id', $logged_user);
                    })
                    ->first();

                if(!$chat_validate) {
                    return redirect()->route('inicial');
                }

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

        if(Agent::isDesktop()) {
            if(\Request::ajax()) {
                return response()->json([
                    'trabalho' => view('inc.chat', compact('destinatario', 'tipo', 'chat_id', 'destinatario_id', 'messages'))->render(),
                    'new_messages_trabalho' => $new_messages_trabalho,
                    'new_messages_pessoal' => $new_messages_pessoal,
                    'destinatario_slug' => $destinatario_slug
                ]);
            } else {
                if($chat_id) {
                    if($tipo == 'trabalho') {
                        $section = 'pessoal';
                        $column = 'from_id';
                    } else {
                        $section = 'trabalho';
                        $column = 'to_id';
                    }

                    $chats = Chat::where($column, $logged_user)
                        ->whereHas('messages', function($query) use($logged_user) {
                            $query->where('deleted', '!=', $logged_user)
                                ->orWhereNull('deleted');
                        })
                        ->withCount(['messages as latest_message' => function($query) {
                            $query->select(DB::raw('max(created_at)'));
                        }])
                        ->orderByRaw("id = $chat_id DESC")
                        ->orderByDesc('latest_message')
                        ->get();
                } else {
                    $palavra_chave = $destinatario->nome;

                    $trabalhos = Trabalho::where('id', $id)->paginate(1);
                }

                return view('pagina-inicial', compact('destinatario', 'destinatario_id', 'tipo', 'section', 'chats', 'chat_id', 'messages', 'palavra_chave', 'trabalhos'));
            }
        } else {
            return view('mobile.inc.chat', compact('destinatario', 'tipo', 'chat_id', 'destinatario_id', 'messages'));
        }
    }

    // Listar os ultimos trabalhos que enviaram uma mensagem
    public function pessoal()
    {
        if(Auth::guard('web')->check()) {
            $user_id = Auth::guard('web')->user()->id;

            $chats = Chat::where('from_id', $user_id)
                ->whereHas('messages', function($query) use($user_id) {
                    $query->where('deleted', '!=', $user_id)
                        ->orWhereNull('deleted');
                })
                ->withCount(['messages as latest_message' => function($query) {
                    $query->select(DB::raw('max(created_at)'));
                }])
                ->with(['messages' => function($query) {
                    $query->orderByDesc('id');
                }])
                ->orderByDesc('latest_message')
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
                ->whereHas('messages', function($query) use($user_id) {
                    $query->where('deleted', '!=', $user_id)
                        ->orWhereNull('deleted');
                })
                ->withCount(['messages as latest_message' => function($query) {
                    $query->select(DB::raw('max(created_at)'));
                }])
                ->with(['messages' => function($query) {
                    $query->orderByDesc('id');
                }])
                ->orderByDesc('latest_message')
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
