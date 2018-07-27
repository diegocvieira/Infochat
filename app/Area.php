<?php
namespace App;

use Illuminate\Database\Eloquent\Model;

class Area extends Model
{
    protected $table = 'areas';
    protected $fillable = ['titulo', 'slug'];
    public $timestamps = false;

    public function categorias()
    {
        return $this->HasMany('App\Categoria');
    }
}
