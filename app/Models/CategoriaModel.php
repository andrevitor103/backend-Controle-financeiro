<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CategoriaModel extends Model
{
    use HasFactory;

    protected $table = "categoria";
    protected $primarykey = "id";
    protected $fillable = [
        'DESCRICAO',
        'ID_USUARIO'
    ];

    function despesas()
    {
        return $this->belongsTo(DespesasModel::class, 'ID_CATEGORIA', 'ID');
    }

}
