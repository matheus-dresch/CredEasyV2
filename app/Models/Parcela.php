<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Parcela extends Model
{
    use HasFactory;

    protected $table = 'parcela';
    public $timestamps = false;

    protected $fillable = [
        'valor',
        'emprestimo_id',
        'numero',
        'data_vencimento'
    ];

    protected $dates = ['data_pagamento', 'data_vencimento'];

    public function emprestimo()
    {
        return $this->belongsTo(Emprestimo::class);
    }
}
