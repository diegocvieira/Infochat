<?php
namespace App;

use Illuminate\Database\Eloquent\Model;

class Estado extends Model
{
    protected $table = 'estados';
    protected $fillable = ['pais_id', 'region_id', 'title', 'letter', 'iso', 'status', 'slug', 'letter_lc', 'order'];
    public $timestamps = false;
}
