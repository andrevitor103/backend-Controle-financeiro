<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFormasPagamento extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('formas_pagamento', function (Blueprint $table) {
            $table->id();
            $table->string('DESCRICAO');
            $table->decimal('SALDO_LIMITE');
            $table->date('DATA_COBRANCA');
            $table->date('DATA_ATUALIZACAO');
            $table->string('DESCRICAO_ATUALIZACAO');
            $table->integer('ID_USUARIO');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('formas_pagamento');
    }
}
