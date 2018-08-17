<?php
namespace App\Http\ViewComposers;

use Illuminate\Contracts\View\View;
use Illuminate\Users\Repository as UserRepository;
use Auth;

class NewMessagesComposer
{
	public function compose(View $view)
	{
        if(Auth::guard('web')->check()) {
            $new_messages = app('App\Http\Controllers\MessageController')->newMessages();

            $new_messages_pessoal = $new_messages['pessoal'];
            $new_messages_trabalho = $new_messages['trabalho'];
        } else {
            $new_messages_pessoal = null;
            $new_messages_trabalho = null;
        }

		$view->with('new_messages_pessoal', $new_messages_pessoal)->with('new_messages_trabalho', $new_messages_trabalho);
	}
}
