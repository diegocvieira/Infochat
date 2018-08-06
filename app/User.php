<?php
namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use Notifiable;

    protected $fillable = [
        'nome', 'imagem', 'email', 'password'
    ];

    protected $hidden = [
        'password', 'remember_token',
    ];

    public function trabalho()
    {
        return $this->hasOne('App\Trabalho');
    }

    public function favorito($id)
    {
        return $this->hasMany('App\Favoritar')->where('trabalho_id', $id)->first();
    }
}
