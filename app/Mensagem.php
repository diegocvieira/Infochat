<?php
namespace App;

use Illuminate\Database\Eloquent\Model;

class Mensagem extends Model
{
    protected $table = 'mensagens';
    protected $fillable = ['remetente_id', 'destinatario_id', 'tipo', 'mensagem'];
}
