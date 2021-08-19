<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDespesaDetalhesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('despesa_detalhes', function (Blueprint $table) {
            $table->id();
            $table->decimal('VALOR_PARCELA');
            $table->date('DATA_VENCIMENTO');
            $table->date('DATA_PAGAMENTO');
            $table->decimal('JUROS');
            $table->decimal('DESCONTO');
            $table->integer('ID_DESPESA');
            $table->integer('NUMERO_PARCELA');
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
        Schema::dropIfExists('despesa_detalhes');
    }
}
