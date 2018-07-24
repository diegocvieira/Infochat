<?php
namespace App;

use Illuminate\Database\Eloquent\Model;

class HorarioAtendimento extends Model
{
    protected $table = 'horarios_funcionamento';
    protected $fillable = ['dia', 'de_manha', 'ate_tarde', 'de_tarde', 'ate_noite', 'trabalho_id'];
    public $timestamps = false;
}
