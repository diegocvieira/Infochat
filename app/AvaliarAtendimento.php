<?php
namespace App;

use Illuminate\Database\Eloquent\Model;

class AvaliarAtendimento extends Model
{
    protected $table = 'avaliacoes_atendimento';
    protected $fillable = ['trabalho_id', 'user_id', 'nota'];
}
