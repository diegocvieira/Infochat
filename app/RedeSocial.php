<?php
namespace App;

use Illuminate\Database\Eloquent\Model;

class RedeSocial extends Model
{
    protected $table = 'redes_sociais';
    protected $fillable = ['url', 'trabalho_id'];
    public $timestamps = false;
}
