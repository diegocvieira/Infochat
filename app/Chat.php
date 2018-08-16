<?php

namespace App;
use Auth;

use Illuminate\Database\Eloquent\Model;

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
}
