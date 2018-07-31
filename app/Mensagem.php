<?php
namespace App;

use Illuminate\Database\Eloquent\Model;

class Mensagem extends Model
{
    protected $table = 'mensagens';
    protected $fillable = ['remetente_id', 'destinatario_id', 'tipo', 'mensagem'];

    public function user_remetente()
    {
        return $this->BelongsTo('App\User', 'remetente_id');
    }

    public function user_destinatario()
    {
        return $this->BelongsTo('App\User', 'destinatario_id');
    }
}
