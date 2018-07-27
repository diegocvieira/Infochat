<?php
namespace App;

use Illuminate\Database\Eloquent\Model;

class Tag extends Model
{
    protected $table = 'tags';
    protected $fillable = ['tag', 'trabalho_id'];
    public $timestamps = false;
}
