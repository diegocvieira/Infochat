<?php
namespace App;
use Illuminate\Database\Eloquent\Model;

class Categoria extends Model
{
    protected $table = 'categorias';
    protected $fillable = ['titulo', 'slug'];
    public $timestamps = false;

    public function subcategorias()
    {
        return $this->HasMany('App\Subcategoria');
    }
}
