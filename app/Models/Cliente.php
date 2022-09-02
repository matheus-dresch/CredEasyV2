<?php

namespace App\Models;

use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
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

    public function proximaParcela()
    {
        $parcela = Parcela::select('parcela.emprestimo_id', 'parcela.data_vencimento', 'parcela.valor')
            ->join('emprestimo', 'parcela.emprestimo_id', 'emprestimo.id')
            ->where('cliente_id', $this->id)
            ->where('parcela.status', '!=', 'PAGA')
            ->orderBy('parcela.data_vencimento')
            ->limit(1)
            ->first();

        return $parcela;
    }
}
