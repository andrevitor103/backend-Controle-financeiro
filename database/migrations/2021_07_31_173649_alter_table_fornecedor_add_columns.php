<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterTableFornecedorAddColumns extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('fornecedor', function (Blueprint $table) {
            $table->string('NOME_FANTASIA');
            $table->string('SITUACAO_CADASTRAL');
            $table->string('TELEFONE');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('fornecedor', function (Blueprint $table) {
            //
        });
    }
}
