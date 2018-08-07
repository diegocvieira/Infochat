<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Cookie;
use App\AvaliarAtendimento;
use App\Avaliar;
use DB;

class Trabalho extends Model
{
    protected $table = 'trabalhos';
    protected $fillable = [
        'user_id', 'tipo', 'nome', 'area_id', 'imagem', 'descricao', 'cidade_id', 'logradouro', 'numero', 'bairro', 'complemento', 'slug', 'status', 'cep', 'email'
    ];
    protected $dates = ['created_at', 'updated_at'];

    public function tipoNome($tipo)
    {
        return $tipo == '1' ? 'profissional' : 'estabelecimento';
    }

    public function calc_atendimento($id)
    {
        $atendimento = AvaliarAtendimento::select(DB::raw('CEILING((SUM(likes) * 100) / (SUM(likes) + SUM(dislikes))) as nota'))
            ->where('trabalho_id', $id)
            ->first();

        $nota = $atendimento->nota;

        if(!$nota) {
            $nota = 100;
        }

        return $nota;
    }

    public function calc_avaliacao($id)
    {
        $avaliacao = Avaliar::select(DB::raw('ROUND((SUM(nota) / COUNT(id)), 1) as nota'))
            ->where('trabalho_id', $id)
            ->first();

        $nota = $avaliacao->nota;

        if(!$nota) {
            $nota = 5 . '.0';
        }

        return $nota;
    }

    public function user()
    {
        return $this->belongsTo('App\User');
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
        return $this->hasMany('App\HorarioAtendimento')->orderBy('dia', 'asc');
    }

    public function area()
    {
        return $this->belongsTo('App\Area');
    }

    public function avaliacoes()
    {
        return $this->hasMany('App\Avaliar')->whereNotNull('descricao')->orderBy('created_at', 'desc');
    }

    public function notas_atendimento()
    {
        return $this->hasMany('App\AvaliarAtendimento');
    }

    public function cidade()
    {
        return $this->belongsTo('App\Cidade');
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

    public function scopeFiltroCidade($query)
    {
        return $query->where('cidade_id', Cookie::get('sessao_cidade_id'));
    }

    public function scopeFiltroStatus($query)
    {
        return $query->where('status', 1);
    }
}
