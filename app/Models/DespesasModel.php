<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DespesasModel extends Model
{
    use HasFactory;

    protected $table =  "despesa";
    protected $primarykey = "id";

    protected $fillable = [
        'VALOR_DESPESA',
        'TOTAL_PARCELAS',
        'DATA_COMPRA',
        'ID_FORNECEDOR',
        'ID_CATEGORIA',
        'ID_CONDICAOPAGAMENTO',
        'JUROS_ATRASO',
        'DESCONTO',
        'ID_FORMA_PAGAMENTO',
        'ID_USUARIO'
        
    ];

    public function detalhes()
    {
        return $this->hasMany(DespesasDetalhesModel::class,'ID_DESPESA');
    }
    
    public function formaPagamento()
    {
        return $this->hasOne(FormaPagamentoModel::class, 'id', 'ID_FORMA_PAGAMENTO');
    }
    
    public function fornecedor()
    {
        return $this->hasOne(FornecedorModel::class, 'id', 'ID_FORNECEDOR');    
    }

    function categoria()
    {
        return $this->hasOne(CategoriaModal::class, 'ID', 'ID_CATEGORIA');
    }

    public function parcelas()
    {
        return $this->hasMany(ParcelasModel::class,'DESPESA_ID');
    }
}
