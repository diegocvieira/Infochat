<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class NoResponse extends Model
{
    protected $table = 'no_responses';
    protected $fillable = ['user_id', 'work_id'];
    public $timestamps = false;
}
