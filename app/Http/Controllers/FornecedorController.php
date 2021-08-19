<?php

namespace App\Http\Controllers;

use App\Http\Requests\FornecedorRequest;
use App\Models\FornecedorModel;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class FornecedorController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index($id)
    {
        $fornecedor = FornecedorModel::whereIn('ID_USUARIO', [$id])->get();
        return response()->json(['fornecedores' => $fornecedor]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(FornecedorRequest $request)
    {
        $fornecedorNew = $request->all();
        try {
                $fornecedorNew = FornecedorModel::create($fornecedorNew);
        } catch (Exception $e) {
            return response()->json(["Erro" => $e->getMessage()]);
        }
        return response()->json(["novo_fornecedor" => $fornecedorNew]);
    }

    function searchCnpjApi($documento)
    {
        $request = Http::get("https://brasilapi.com.br/api/cnpj/v1/$documento");
        if($request->successful())
        {
            return response()->json(['fornecedor_api' => $request->json()]);
        }
        return response()->json(['erro' => 'fornecedor não encontrado']);
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
    public function update(FornecedorRequest $request, $id)
    {
        
        $fornecedorNewUpdate = $request->all();
        try {
            $fornecedorOldUpdate = FornecedorModel::find($id);
            $fornecedorOldUpdate->update($fornecedorNewUpdate);
        } catch (Exception $e) {
            return response()->json(['erro' => $e->getMessage()]);
        }

        return response()->json(['fornecedor_atualizado' => $fornecedorOldUpdate]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $fornecedorDelete = FornecedorModel::find($id);
        if(!$this->isValid($fornecedorDelete))
        {
            return $this->responseJsonMessage('erro', 'Fornecedor não localizado');
        }
        $fornecedorDelete->delete();
        return $this->responseJsonMessage('fornecedor_deletado', true);
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



}
