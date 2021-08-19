<?php

namespace App\Http\Requests;

use Attribute;
use Illuminate\Foundation\Http\FormRequest;

class DespesaRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'VALOR_DESPESA' => 'required',
            'TOTAL_PARCELAS' => 'required',
            'DATA_COMPRA' => 'required',
            'ID_FORNECEDOR' => 'required',
            'ID_CATEGORIA' => 'nullable',
            'ID_FORMA_PAGAMENTO' => 'required',
            'ID_USUARIO' => 'required'

        ];
    }

    public function attributes()
    {
        return [
            'VALOR_DESPESA' => 'Valor',
            'TOTAL_PARCELAS' => 'Total de parcelas',
            'DATA_COMPRA' => 'Data da compra',
            'ID_FORNECEDOR' => 'Fornecedor',
            'ID_FORMA_PAGAMENTO' => 'Forma pagamento',
            'ID_USUARIO' => 'Usuário'
        ];
    }
}
