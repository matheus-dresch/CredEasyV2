<?php

namespace App\Models;

use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Laravel\Sanctum\HasApiTokens;

class Cliente extends Model implements Authenticatable
{
    use HasFactory, HasApiTokens;

    protected $table = 'cliente';
    public $timestamps = false;

    protected $hidden = ['senha', 'emprestimos'];

    protected $fillable = [
        'cpf',
        'nome',
        'numero_celular',
        'endereco',
        'profissao',
        'renda',
        'email',
        'senha',
        'tipo'
    ];

    public function emprestimos()
    {
        return $this->hasMany(Emprestimo::class);
    }

    public function getAuthIdentifierName()
    {
        return 'email';
    }

    public function getAuthIdentifier()
    {
        return $this->attributes['email'];
    }

    public function getAuthPassword()
    {
        return $this->attributes['senha'];
    }

    public function setRememberToken($value)
    {

    }

    public function getRememberToken()
    {
        return '';
    }

    public function getRememberTokenName()
    {
        return '';
    }


}
