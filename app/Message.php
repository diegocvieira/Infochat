<?php
namespace App;

use Illuminate\Database\Eloquent\Model;

class Message extends Model
{
    protected $table = 'messages';
    protected $fillable = ['chat_id', 'user_id', 'message'];
    protected $dates = ['created_at', 'read_at'];
    public $timestamps = false;

    public function chat()
    {
        return $this->belongsTo('App\Chat');
    }

    public function user()
    {
        return $this->belongsTo('App\User');
    }
}
