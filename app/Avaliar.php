<?php
namespace App;

use Illuminate\Database\Eloquent\Model;

class Avaliar extends Model
{
    protected $table = 'avaliacoes';
    protected $fillable = ['trabalho_id', 'user_id', 'avaliacao'];
    public $timestamps = false;
}
