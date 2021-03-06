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

    public function scopeOrdered($query)
    {
        return $query->select('titulo', 'slug')
                    ->distinct()
                    ->orderByRaw("FIELD(titulo, 'Outros'), 'titulo' ASC")
                    ->orderBy('titulo', 'ASC');
    }

    public function scopeTypeFilter($query, $type)
    {
        if($type == 'profissionais') {
            $type = 1;
        } else if($type == 'estabelecimentos') {
            $type = 2;
        } else {
            $type = '';
        }

        if($type) {
            return $query->where('tipo', $type);
        }
    }
}
