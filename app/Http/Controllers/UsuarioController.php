<?php

namespace App\Http\Controllers;

use App\Http\Requests\UsuariosRequest;
use App\Models\UsuarioModel;
use Exception;
use Hamcrest\Core\IsTypeOf;
use Illuminate\Contracts\Encryption\DecryptException;
use Illuminate\Http\Request;
use phpDocumentor\Reflection\Types\Boolean;
use Symfony\Component\HttpFoundation\Test\Constraint\ResponseFormatSame;

class UsuarioController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index($id)
    {
        $user = UsuarioModel::find($id);
        return response()->json(['user' => $user]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(UsuariosRequest $request)
    {
        try {
            $requestUser = $request->all();
            $newUser = UsuarioModel::create($requestUser);

            return response()->json(['novo_usuario' => $newUser]);
        } catch (Exception $e) {
            return $e->getMessage();
        }
    }
    
    public function encrypt($valueForEncrypt)
    {
        return encrypt($valueForEncrypt);
    }

    public function login(Request $request)
    {
            $userRequest = $request->all();
            $user = $this->confirmUsernameAndPassword($userRequest['username'], $userRequest['user_password']);
            if($user)
            {
                return response()->json(['Logado com sucesso' => true, 'id_user' => $user->id]);
            }
            return response()->json(['Dados inválidos' => false]);
    }

    public function confirmUsernameAndPassword(string $username, string $password)
    {
        return UsuarioModel::where('username', $username)
                ->where('user_password', $password)
                ->first();
    }

    public function descripty($valueForDecrypt)
    {
        try {
            return decrypt($valueForDecrypt);
         } catch (DecryptException $e) {
            return response()->json(['Erro' => $e->getMessage()]);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(UsuariosRequest $request, $id)
    {
        try {
            $oldUser = UsuarioModel::find($id);
            $requestUser = $request->all();
            
            $oldUserIsValid = $this->isValid($oldUser);

            if($oldUserIsValid != 1)
            {
               return $this->responseJsonMessage('erro', 'Usuário não localizado na base de dados');
            }

            return $this->isUpdatedSuccessfully($oldUser->update($requestUser));

        } catch (Exception $e) {
            return response()->json(['erro' => $e->getMessage()]);
        }
    }

    public function isValid($valid)
    {
        if(!$valid)
        {
            return false;
        }
        return true;
    }

    public function responseJsonMessage(String $title, $message)
    {
        return response()->json([$title => $message]);
    }

    public function isUpdatedSuccessfully(bool $validateUpdate)
    {
        if($validateUpdate)
        {
            return $this->responseJsonMessage('usuario_atualizado_com_sucesso', true);
        }
        return $this->responseJsonMessage('erro', 'Erro ao atualizar usuário');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $userDelete = UsuarioModel::find($id);
        if(!$this->isValid($userDelete))
        {
            return $this->responseJsonMessage('erro', 'Usuário não localizado');
        }
        $userDelete->delete();
        return $this->responseJsonMessage('usuario_deletado', true);
    }
 }
