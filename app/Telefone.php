<?php
namespace App;

use Illuminate\Database\Eloquent\Model;

class Telefone extends Model
{
    protected $table = 'telefones';
    protected $fillable = ['fone', 'trabalho_id'];
    public $timestamps = false;
}
