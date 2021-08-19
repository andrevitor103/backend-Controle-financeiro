<?php

namespace App\Http\Controllers;

use App\Http\Requests\FormaPagamentoRequest;
use App\Models\DespesasModel;
use App\Models\FormaPagamentoModel;
use Exception;
use Illuminate\Http\Request;

class FormaPagamentoController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index($id)
    {
        $formaPagamento = FormaPagamentoModel::whereIn('ID_USUARIO', [$id])->get();
        return response()->json(['formas_de_pagamento' => $formaPagamento]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(FormaPagamentoRequest $request)
    {
        $formaPagamentoNew = $request->all();

        try {
            $formaPagamentoNew = FormaPagamentoModel::create($formaPagamentoNew);
        }catch(Exception $e){
            return response()->json(['Error' => $e->getMessage()]);
        }
        return response()->json(['nova_forma_pagamento' => $formaPagamentoNew]);
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
    public function update(FormaPagamentoRequest $request, $id)
    {
        try {
            $newFormaPagamento = $request->all();
            $oldFormaPagamento = FormaPagamentoModel::find($id);
            $oldFormaPagamento->update($newFormaPagamento);
            return response()->json(['Forma_pagamento_atualizada' => $newFormaPagamento]);
        } catch (Exception $e) {
            return response()->json(['erro' => $e->getMessage()]);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
     public function destroy($id)
    {
        $fornecedorDelete = FormaPagamentoModel::find($id);
        if(!$this->isValid($fornecedorDelete))
        {
            return $this->responseJsonMessage('erro', 'Forma de pagamento não localizada');
        }
        $fornecedorDelete->delete();
        return $this->responseJsonMessage('forma_pagamento_deleta', true);
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
