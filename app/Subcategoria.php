<?php
namespace App;

use Illuminate\Database\Eloquent\Model;

class Subcategoria extends Model
{
    protected $table = 'subcategorias';
    protected $fillable = ['titulo', 'slug', 'categoria_id'];
    public $timestamps = false;

    public function categoria()
    {
        return $this->BelongsTo('App\Categoria');
    }
}
