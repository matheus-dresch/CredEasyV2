<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('parcela', function (Blueprint $table) {
            $table->id();

            $table->float('valor', 10, 2, true);
            $table->integer('numero');
            $table->float('taxa_multa');
            $table->float('valor_pago');

            $table->dateTime('data_vencimento');
            $table->dateTime('data_pagamento')->nullable();

            $table->string('status', 32)->default('AEBRTA');

            $table->foreignId('emprestimo_id')
                ->constrained('emprestimo')
                ->cascadeOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('parcela');
    }
};
