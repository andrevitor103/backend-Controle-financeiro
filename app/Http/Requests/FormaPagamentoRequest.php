<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class FormaPagamentoRequest extends FormRequest
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
            'DESCRICAO' => 'required|min:2',
            'SALDO_LIMITE' => 'required|numeric',
            'DATA_COBRANCA' => 'required|numeric'
        ];
    }

    public function attributes(){
        return [
            'DESCRICAO' => 'Descrição',
            'SALDO_LIMITE' => 'Limite de crédito',
            'DATA_COBRANCA' => 'Data de cobrança'
        ];
    }
}
