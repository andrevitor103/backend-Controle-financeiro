<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ParcelasModel extends Model
{
    use HasFactory;
    
    protected $table = "parcelas";
    protected $primarykey = "ID";
     protected $fillable = [
        'VALOR_PARCELA',
        'DATA_VENCIMENTO',
        'DATA_PAGAMENTO',
        'JUROS',
        'DESCONTO',
        'DESPESA_ID',
        'NUMERO_PARCELA'
    ];

     public function despesas()
    {
        return $this->belongsTo(DespesasModel::class, 'DESPESA_ID', 'id');
    }

}
