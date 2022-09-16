<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Emprestimo extends Model
{
    use HasFactory;

    protected $table = 'emprestimo';
    public $timestamps = false;

    protected $fillable = [
        'nome',
        'valor',
        'data_solicitacao',
        'valor_final',
        'qtd_parcelas',
        'cliente_id'
    ];

    protected $hidden = ['parcelas'];

    public function cliente()
    {
        return $this->belongsTo(Cliente::class);
    }

    public function parcelas()
    {
        return $this->hasMany(Parcela::class);
    }

    public function proximaParcela()
    {
        $proximaParcela = $this->parcelas()->where('status', '!=' ,'PAGA')->first();

        return $proximaParcela ? $proximaParcela->numero : null;
    }
}
