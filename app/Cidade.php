<?php
namespace App;

use Illuminate\Database\Eloquent\Model;

class Cidade extends Model
{
    protected $table = 'cidades';
    protected $fillable = ['state_id', 'title', 'iso', 'iso_ddd', 'status', 'slug', 'order'];
    public $timestamps = false;

    public function estado()
    {
        return $this->BelongsTo('App\Estado');
    }
}
