<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FornecedorModel extends Model
{
    use HasFactory;
    
    protected $table = "fornecedor";
    protected $primarykey = "id";
    /*protected $fillable = [
        'RAZAO_SOCIAL',
        'DOCUMENTO',
        'ID_USUARIO'
    ];*/
    protected $guarded = [];

    public function despesa()
    {
        return $this->belongsTo(DespesasModel::class, 'ID_FORNECEDOR', 'id');    
    }
}
