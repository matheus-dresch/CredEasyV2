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
        Schema::create('emprestimo', function (Blueprint $table) {
            $table->id();

            $table->string('nome', 32);

            $table->string('valor', 32);
            $table->string('valor_final', 32);
            $table->float('taxa_juros', 10, 2, true)->default(1);
            $table->integer('qtd_parcelas');
            $table->string('status', 32)->default('SOLICITADO');

            $table->dateTime('data_solicitacao');
            $table->dateTime('data_quitacao')->nullable();

            $table->foreignId('cliente_id')
                ->constrained('cliente')
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
        Schema::dropIfExists('emprestimo');
    }
};
