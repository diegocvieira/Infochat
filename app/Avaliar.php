<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Avaliar extends Model
{
    protected $table = 'avaliacoes';
    protected $fillable = ['trabalho_id', 'user_id', 'nota', 'descricao'];

    public function user()
    {
        return $this->belongsTo('App\User');
    }
}
