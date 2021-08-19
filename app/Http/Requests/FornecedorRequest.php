<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class FornecedorRequest extends FormRequest
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
            'RAZAO_SOCIAL' => 'required',
            'DOCUMENTO' => 'required|min:6',
        ];
    }

    public function attributes(){
        return [
            'RAZAO_SOCIAL' => 'Razão social',
            'DOCUMENTO' => 'Documento',
        ];
    }
}
