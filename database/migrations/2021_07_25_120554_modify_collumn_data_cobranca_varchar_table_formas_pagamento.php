<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ModifyCollumnDataCobrancaVarcharTableFormasPagamento extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('formas_pagamento', function (Blueprint $table) {
           $table->string('DATA_COBRANCA'); 
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('formas_pagamento', function (Blueprint $table) {
            //
        });
    }
}
