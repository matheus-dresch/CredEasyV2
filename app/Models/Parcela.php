<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Parcela extends Model
{
    use HasFactory;

    protected $table = 'parcela';
    public $timestamps = false;

    public function emprestimo()
    {
        return $this->belongsTo(Emprestimo::class);
    }
}
