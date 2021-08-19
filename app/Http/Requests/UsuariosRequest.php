<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UsuariosRequest extends FormRequest
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
            'username' => 'required|min:3',
            'user_password' => 'required|min:8',
            'email' => 'required|email'
        ];
    }
    public function attributes()
    {
        return [
            'username' => 'Usuário',
            'user_password' => 'Senha',
            'email' => 'Email',
            'picture_user' => 'Imagem perfil'
        ];
    }
}
