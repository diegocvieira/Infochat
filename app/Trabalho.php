<?php
namespace App;

use Illuminate\Database\Eloquent\Model;

class Trabalho extends Model
{
    protected $table = 'trabalhos';
    protected $fillable = [
        'user_id', 'tipo', 'nome', 'area_id', 'imagem', 'descricao', 'cidade_id', 'logradouro', 'numero', 'bairro', 'complemento', 'slug', 'status'
    ];
    protected $dates = ['created_at', 'updated_at'];
}
