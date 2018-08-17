<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Auth;

class Chat extends Model
{
    protected $table = 'chats';
    protected $fillable = ['from_id', 'to_id', 'close'];
    protected $dates = ['created_at'];
    public $timestamps = false;

    public function messages()
    {
        return $this->hasMany('App\Message', 'chat_id');
    }

    public function user_from()
    {
        return $this->BelongsTo('App\User', 'from_id');
    }

    public function user_to()
    {
        return $this->BelongsTo('App\User', 'to_id');
    }

    public function count_new_messages()
    {
        $user_id = Auth::guard('web')->user()->id;

        return $this->hasMany('App\Message', 'chat_id')
            ->where(function($q) use($user_id) {
                $q->where('deleted', '!=', $user_id)
                    ->orWhereNull('deleted');
            })
            ->where('user_id', '!=', $user_id)
            ->whereNull('read_at')->count();
    }
}
