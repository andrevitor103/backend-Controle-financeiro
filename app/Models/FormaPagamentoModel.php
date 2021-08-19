<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FormaPagamentoModel extends Model
{
    use HasFactory;

    protected $table = "formas_pagamento";
    protected $primarykey = "id";
    
    /*protected $fillable = [
        'DESCRICAO',
        'SALDO_LIMITE',
        'DATA_COBRANCA',
        'ID_USUARIO'
    ];*/

    protected $guarded = [];

    function despesa()
    {
        return $this->belongsTo(DespesasModel::class, 'ID_FORMA_PAGAMENTO', 'id');
    }

}

