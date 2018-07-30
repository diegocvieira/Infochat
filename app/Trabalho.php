<?php
namespace App;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Cookie;
use Auth;

class Trabalho extends Model
{
    protected $table = 'trabalhos';
    protected $fillable = [
        'user_id', 'tipo', 'nome', 'area_id', 'imagem', 'cpf_cnpj', 'descricao', 'cidade_id', 'logradouro', 'numero', 'bairro', 'complemento', 'slug', 'status', 'cep', 'email'
    ];
    protected $dates = ['created_at', 'updated_at'];

    public function user()
    {
        return $this->BelongsTo('App\User');
    }

    public function telefones()
    {
        return $this->hasMany('App\Telefone');
    }

    public function redes()
    {
        return $this->hasMany('App\RedeSocial');
    }

    public function tags()
    {
        return $this->hasMany('App\Tag');
    }

    public function horarios()
    {
        return $this->hasMany('App\HorarioAtendimento');
    }

    public function area()
    {
        return $this->BelongsTo('App\Area');
    }

    public function scopeFiltroArea($query, $area)
    {
        if($area) {
            return $query->whereHas('area', function($q) use($area) {
                $q->where('slug', $area);
            });
        }
    }

    public function scopeFiltroTag($query, $tag)
    {
        if($tag) {
            return $query->whereHas('tags', function($q) use($tag) {
                $q->where('tag', 'LIKE', '%' . $tag . '%');
            });
        }
    }

    public function scopeFiltroPalavraChave($query, $palavra_chave)
    {
        if($palavra_chave && $palavra_chave != 'area') {
            return $query->where('nome', 'LIKE', '%' . $palavra_chave . '%')->orWhereHas('tags', function($q) use($palavra_chave) {
                $q->where('tag', 'LIKE', '%' . $palavra_chave . '%');
            });
        }
    }

    public function scopeFiltroOrdem($query, $ordem)
    {
        if($ordem) {
            if($ordem == 'a_z') {
                return $query->orderBy('nome', 'asc');
            } else if($ordem == 'populares') {
                return $query->orderBy('pageviews', 'desc');
            }
        }
    }

    public function scopeFiltroTipo($query, $tipo)
    {
        if($tipo == 'profissionais') {
            $tipo = 1;
        } else if($tipo == 'estabelecimentos') {
            $tipo = 2;
        } else {
            $tipo = '';
        }

        if($tipo) {
            return $query->where('tipo', $tipo);
        } else {
            return $query->where('tipo', 1)->orWhere('tipo', 2);
        }
    }

    protected static function boot()
	{
	    parent::boot();

		static::addGlobalScope('cidade', function(Builder $builder) {
        	$builder->where('cidade_id', Cookie::get('sessao_cidade_id'));
	    });

        static::addGlobalScope('ativo', function(Builder $builder) {
        	$builder->where('status', 1);
	    });

        // Remover o usuario logado das buscas
        if(Auth::guard('web')->check()) {
            static::addGlobalScope('trabalho_logado', function(Builder $builder) {
            	$builder->where('user_id', '!=', Auth::guard('web')->user()->id);
    	    });
        }
	}
}
