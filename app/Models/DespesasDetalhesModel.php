<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DespesasDetalhesModel extends Model
{
    use HasFactory;

    protected $table = "despesa_detalhes";
    protected $primarykey = "id";
    protected $fillable = [
        'VALOR_PARCELA',
        'DATA_VENCIMENTO',
        'DATA_PAGAMENTO',
        'JUROS',
        'DESCONTO',
        'ID_DESPESA',
        'NUMERO_PARCELA'
    ];

     public function despesas()
    {
        return $this->belongsTo(DespesasModel::class, 'ID_DESPESA', 'id');
    }

}
