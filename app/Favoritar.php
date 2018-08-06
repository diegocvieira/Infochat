<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Favoritar extends Model
{
    protected $table = 'favoritos';
    protected $fillable = ['trabalho_id', 'user_id'];
    public $timestamps = false;
}
