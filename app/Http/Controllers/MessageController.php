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
use Illuminate\Pagination\Paginator;
use App\NoResponse;

class MessageController extends Controller
{
    // Enviar mensagem
    public function send(Request $request)
    {
        if(Auth::guard('web')->check()) {
            $user_logged = Auth::guard('web')->user()->id;

            if($request->chat_id) {
                $chat_id = $request->chat_id;
            } else {
                $c = new Chat;
                $c->from_id = $user_logged;
                $c->to_id = $request->work_user;
                $c->created_at = date('Y-m-d H:i:s');
                $c->save();

                $chat_id = $c->id;
            }

            $chat = Chat::where('id', $chat_id)
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
                            $q->whereRaw('created_at = (SELECT MAX(created_at) FROM messages) AND TIMESTAMPDIFF(MINUTE, created_at, NOW()) >= 5');
                        })
                        ->where('id', $chat->id)
                        ->select('to_id', 'from_id')
                        ->first();

                    if($message->save()) {
                        $return['status'] = 1;
                        $return['chat_id'] = $chat_id;

                        /*if($check) {
                            // Name of who send the message
                            $user_from_name = $chat->from_id == $user_logged ? $chat->user_from->nome : $chat->user_to->trabalho->nome;

                            // Chat url
                            $url_type = $chat->from_id == $user_logged ? 'pessoal' : 'trabalho';
                            $url_id = $chat->from_id == $user_logged ? $chat->user_from->id : $chat->user_to->trabalho->id;
                            $chat_url = '/mensagem/chat/show/' . $url_id . '/' . $url_type . '/' . $chat->id;

                            // Pega o token da pessoa que recebe a mensagem
                            $tokenDoDestinatario = $chat->from_id == $user_logged ? $chat->user_to->onesignal_token : $chat->user_from->onesignal_token;

                            if($tokenDoDestinatario != "") {
                                $cabecalho = array(
                                    'Content-Type: application/json',
                                    'Authorization: Basic NTVkOWMxMmQtNmNhMS00Nzk3LThhMGYtYWNmYThlNjNjMmQ2'
                                );

                                // app_id é o seu App Id lá do OneSignal
                                // O 'include_player_ids' são os aparelhos que vão receber a mensagem.
                                // headings é o título e contents é o corpo da mensagem.
                                // No data vai qualquer coisa, eu botei o endereço pra carregar, aí você coloca o certo.
                                $dados = array(
                                    'app_id' => '0305d6ca-af82-4b6a-8c38-253c20043016',
                                    'include_player_ids' => array($tokenDoDestinatario),
                                    'headings' => array('en' => 'Infochat'),
                                    'contents' => array('en' => $user_from_name . ': ' . $request->message),
                                    'data' => array('endereco' => $chat_url)
                                );

                                $curl = curl_init();

                                curl_setopt($curl, CURLOPT_URL, 'https://onesignal.com/api/v1/notifications');
                                curl_setopt($curl, CURLOPT_POST, true);
                                curl_setopt($curl, CURLOPT_HTTPHEADER, $cabecalho);
                                curl_setopt($curl, CURLOPT_POSTFIELDS, json_encode($dados));
                                curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);

                                curl_exec($curl);
                                curl_close($curl);
                            }

                            $email = $check->from_id == $user_logged ? $check->user_to->email : $check->user_from->email;
                            $client['name'] = $user_from_name;
                            $client['image'] = $chat->from_id == $user_logged ? $chat->user_from->imagem : $chat->user_to->trabalho->imagem;
                            $client['message'] = $request->message;
                            $client['id'] = $user_logged;

                            if($email) {
                                if($chat->from_id == $user_logged && !$chat->user_to->claimed) {
                                    $claimed_url = url('/') . '/reivindicar-conta/check/' . app('App\Http\Controllers\ClaimedController')->createToken($email);
                                    $work_url = route('show-work', $chat->user_to->trabalho->slug);

                                    Mail::send('emails.new_message_claimed', ['client' => $client, 'work_url' => $work_url, 'claimed_url' => $claimed_url], function($q) use($email) {
                                        $q->from('no-reply@infochat.com.br', 'Infochat');
                                        $q->to($email)->subject('Nova mensagem');
                                    });
                                } else {
                                    Mail::send('emails.new_message', ['client' => $client, 'chat_url' => url('/') . $chat_url], function($q) use($email) {
                                        $q->from('no-reply@infochat.com.br', 'Infochat');
                                        $q->to($email)->subject('Nova mensagem');
                                    });
                                }
                            }

                            if($chat->from_id == $user_logged) {
                                $count_message = Message::where('chat_id', $chat->id)->where('user_id', $chat->to_id)->count();

                                if($count_message == 0) {
                                    $no_response = NoResponse::firstOrNew(['user_id' => $user_logged, 'work_id' => $chat->to_id]);
                                    $no_response->user_id = $user_logged;
                                    $no_response->work_id = $chat->to_id;
                                    $no_response->save();
                                }

                                $no_response_count = NoResponse::where('work_id', $chat->to_id)->count();

                                if($no_response_count >= 5) {
                                    if($chat->user_to->claimed) {
                                        $work = Trabalho::where('user_id', $chat->to_id)->first();
                                        $work->status = 0;
                                        $work->save();

                                        NoResponse::where('work_id', $chat->to_id)->delete();

                                        Mail::send('emails.disabled_profile', [], function($q) use($chat) {
                                            $q->from('no-reply@infochat.com.br', 'Infochat');
                                            $q->to($chat->user_to->email)->subject('Perfil desativado');
                                        });
                                    } else {
                                        User::find($chat->to_id)->delete();
                                    }
                                }
                            } else {
                                NoResponse::where('work_id', $user_logged)->delete();
                            }
                        }*/
                    } else {
                        $return['status'] = 2;
                    }
                }
            } else {
                $return['status'] = 3;
            }
        } else {
            $user_password = microtime(true);
            $user_email = str_slug($request->message, '-') . microtime(true) . '@unlogged.com';
            $request->replace(['nome' => $request->message, 'email' => $user_email, 'password' => $user_password, 'password_confirmation' => $user_password]);

            $create_user = json_decode(app('App\Http\Controllers\UserController')->create($request), true);

            $return['status'] = $create_user['status'] == true ? 1 : 3;

            if($create_user['status']) {
                $return['user_id'] = $create_user['id'];
            }
        }

        return json_encode($return);
    }

    // Listar as mensagens do chat
    public function list($id, $page, $new_messages = null)
    {
        Paginator::currentPageResolver(function() use ($page) {
            return $page;
        });

        $user_id = Auth::guard('web')->user()->id;

        $messages = Message::where(function($query) use($user_id) {
                $query->where('deleted', '!=', $user_id)
                    ->orWhereNull('deleted');
            })
            ->whereHas('chat', function($query) use($user_id) {
                $query->where('from_id',  $user_id)
                    ->orWhere('to_id', $user_id);
            })
            ->where('chat_id', $id)
            ->orderBy('id', 'desc')
            ->paginate(20);

        $last_msg = Message::where('chat_id', $id)
            ->where('user_id', '!=', $user_id)
            ->select('message')
            ->orderBy('id', 'desc')
            ->first();

        // Read messages
        $this->read($id);

        if($page == 1 && !$new_messages) {
            return $messages;
        } else {
            return response()->json([
                'mensagens' => view('inc.list-mensagens-chat', compact('messages'))->render(),
                'last_msg' => isset($last_msg) ? $last_msg->message : ''
            ]);
        }
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
            ->whereHas('chat.user_to.trabalho')
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
