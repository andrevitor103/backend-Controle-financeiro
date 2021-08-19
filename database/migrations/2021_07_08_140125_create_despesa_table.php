<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDespesaTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('despesa', function (Blueprint $table) {
            $table->id();
            $table->decimal('VALOR_DESPESA');
            $table->bigInteger('TOTAL_PARCELAS');
            $table->date('DATA_COMPRA');
            $table->integer('ID_FORNECEDOR');
            $table->integer('ID_CATEGORIA');
            $table->integer('ID_CONDICAOPAGAMENTO');
            $table->decimal('JUROS_ATRASO');
            $table->decimal('DESCONTO');
            $table->integer('ID_FORMA_PAGAMENTO');
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
        Schema::dropIfExists('despesa');
    }
}
