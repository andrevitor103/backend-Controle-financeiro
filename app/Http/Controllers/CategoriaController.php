<?php

namespace App\Http\Controllers;

use App\Http\Requests\CategoriaRequest;
use App\Models\CategoriaModel;
use Exception;
use Illuminate\Http\Request;

class CategoriaController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index($id)
    {
        $categoria = CategoriaModel::whereIn('ID_USUARIO', [$id])->get();
        return response()->json(['categorias' => $categoria]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(CategoriaRequest $request)
    {
        $categoriaNew = $request->all();
        try {
                $categoriaNew = CategoriaModel::create($categoriaNew);
        } catch (Exception $e) {
            return response()->json(["Erro" => $e->getMessage()]);
        }
        return response()->json(["nova_categoria" => $categoriaNew]);
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
    public function update(CategoriaRequest $request, $id)
    {
        $newCategory = $request->all();

        $oldCategory = CategoriaModel::findOrFail($id);
        $oldCategory->update($newCategory);
        
        
        return response()->json(['category_updated' => $newCategory]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $fornecedorDelete = CategoriaModel::find($id);
        if(!$this->isValid($fornecedorDelete))
        {
            return $this->responseJsonMessage('erro', 'Categoria não localizada');
        }
        $fornecedorDelete->delete();
        return $this->responseJsonMessage('categoria_deletada', true);
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

