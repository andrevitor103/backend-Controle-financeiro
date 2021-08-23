<?php

namespace App\Http\Controllers;

use App\Http\Requests\DespesaRequest;
use App\Models\DespesasDetalhesModel;
use App\Models\DespesasModel;
use Exception;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;

class DespesaController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    public function filterIndex($id, Request $request) {
        try {
            $filter = $request->all();
            return $this->index($id, $filter);
        } catch (Exception $e) {
            return response()->json(['error' => $e->getMessage(), 'status' => $e->status]);
        }
    }

    public function index($id, $filter = null)
    {
        $despesa = new DespesasModel();

        $data = DespesasModel::with(['formaPagamento', 'fornecedor'])
         ->join('despesa_detalhes', 'despesa_detalhes.ID_DESPESA', '=', 'despesa.id')
        ->where('despesa.ID_USUARIO','=' ,$id);

        if ($filter) {
            $data = $this->addFilter($data, $filter);
        }
        $data = $data->get();

        return response()->json(['data' => $data]);
    }

    function filterDashboardLine($userId = 1, Request $request) {
        try {
             $filter = $request->all();
             return $this->dashboardGastosDespesas($userId, $filter);
        } catch (Exception $e) {
            return response()->json(['Dashboard_error' => $e->getMessage()]);
        }
    }

    function dashboardGastosDespesas($id, $filter = null) {
        try {

            $request = DB::table('despesa_detalhes')
            ->join('despesa', 'despesa.id', '=', 'ID_DESPESA')
            ->join('formas_pagamento', 'formas_pagamento.id', '=', 'ID_FORMA_PAGAMENTO')
            ->leftJoin('categoria', 'categoria.id', '=', 'ID_CATEGORIA')
            ->select( 
                DB::raw("CONCAT(MONTH(despesa_detalhes.data_vencimento), '-', YEAR(despesa_detalhes.data_vencimento)) AS MES_ANO"),
                DB::raw('ROUND(AVG(formas_pagamento.SALDO_LIMITE),2) AS LIMITE'), 
                DB::raw('SUM(VALOR_PARCELA) AS GASTOS'), 
                DB::raw("ROUND(AVG(formas_pagamento.SALDO_LIMITE) - SUM(VALOR_PARCELA),2) AS `SALDO`"),
            )
            ->where('despesa.ID_USUARIO', '=', $id);
           
             if ($filter) 
            {   
                $request = $this->addFilter($request, $filter);
            }
            
            $request = $request
                        ->groupBy(DB::raw("YEAR(despesa_detalhes.data_vencimento)"))
                        ->groupBy(DB::raw("MONTH(despesa_detalhes.data_vencimento)"))
                        // ->toSql();
                        ->get();


            return response()->json(['Dashboard' => $request]);

        } catch (Exception $e) {
            return response()->json(['Dashboard_error' => $e->getMessage()]);
        }
    }

    function dashboardLimiteDespesas($id, $filter = null)
    {
        try {
            
            $request = DB::table('despesa_detalhes')
            ->join('despesa', 'despesa.id', '=', 'ID_DESPESA')
            ->join('formas_pagamento', 'formas_pagamento.id', '=', 'ID_FORMA_PAGAMENTO')
            ->join('fornecedor', 'fornecedor.id', '=', 'ID_FORNECEDOR')
            ->leftJoin('categoria', 'categoria.id', '=', 'ID_CATEGORIA')
            ->select(
            'formas_pagamento.DESCRICAO as forma_pagamento', 'formas_pagamento.saldo_limite', 'despesa_detalhes.id',
             DB::raw('SUM(despesa_detalhes.VALOR_PARCELA) as despesas'))
            ->where('despesa.ID_USUARIO', '=', $id);

            if ($filter) 
            {   
                $request = $this->addFilter($request, $filter);
            }
            
            $request = $request
 
            ->groupBy('despesa.ID_FORMA_PAGAMENTO')
                        // ->toSql();
                        ->get();
            return response()->json(['Dashboard' => $request]);
        } catch(Exception $e) {
            return response()->json(['Dashboard_error' => $e->getMessage()]);
        }
    }

    public function listFilter() {
        $filters = [
            "fornecedor" => [
                "table" => "despesa",
                "field" => "id_fornecedor",
                "operation" => "in",
            ],
            "forma_pagamento" => [
                "table" => "despesa",
                "field" => "id_forma_pagamento",
                "operation" => "in",
            ],
            "categoria" => [
                "table" => "despesa",
                "field" => "id_categoria",
                "operation" => "in"
            ],
            "data_vencimento" => [
                "table" => "despesa_detalhes",
                "field" => "DATA_VENCIMENTO",
                "operation" => ">="
            ],
            "data_vencimento_ate" => [
                "table" => "despesa_detalhes",
                "field" => "DATA_VENCIMENTO",
                "operation" => "<="
            ],
             "data_pagamento" => [
                "table" => "despesa_detalhes",
                "field" => "DATA_PAGAMENTO",
                "operation" => ">="
            ],
             "data_pagamento_ate" => [
                "table" => "despesa_detalhes",
                "field" => "DATA_PAGAMENTO",
                "operation" => "<="
            ],
             "pagas" => [
                "table" => "despesa_detalhes",
                "field" => "DATA_PAGAMENTO",
                "operation" => "notNull"
            ],
             "abertas" => [
                "table" => "despesa_detalhes",
                "field" => "DATA_PAGAMENTO",
                "operation" => "null"
            ],
    ];
        return $filters;
    }


    public function addFilter($query, $filters) {

        $searchKeyFilter = array_keys($filters);
        
        array_map(function ($labelFilter) use ($query, $filters) {
            $newfilter = $this->getFilterSearch($labelFilter, $this->listFilter());
            $values =  $filters[$labelFilter];
            if (isset($values[0])) {
                if($newfilter != null && $values[0] != null) {
                    $query = $this->filterQuery($query, $newfilter, $values);
            }    
            }
        }, $searchKeyFilter);

        return $query;
    }

    public function filterQuery($query, $filter, $values) {
       
        if (isset($values[0])) {
            $values = explode(",",  $values[0]);
        }

        try {
            if ($filter["operation"] == "notNull") {
                return $query->whereNotNull($filter["table"].".".$filter["field"]); 
            }
            else if ($filter["operation"] == "null") {
                return $query->whereNull($filter["table"].".".$filter["field"]); 
            }
            if ($filter["operation"] == "in") {
                return $query->whereIn($filter["table"].".".$filter["field"], $values); 
            } else {
                return $query->where($filter["table"].".".$filter["field"], $filter["operation"], $values);
            }
        } catch (Exception $e) {
            return $e->getMessage();
        }   
    }


     public function getFilterSearch($searchFilter, $listFilters) {
        return $listFilters[$searchFilter] ?? null;
    }
    
    public function filterDashboard($userId = 1, Request $request) {
        $filter = $request->all();
        return $this->dashboardLimiteDespesas($userId, $filter);
        return response()->json(['filter' => $filter]);
    }

    public function despesasInfo($id)
    {
        try {
            $data = DespesasModel::with(['formaPagamento', 'fornecedor', 'detalhes'])
            ->whereIn('ID_USUARIO', [$id])
            ->get();

            return response()->json(['detalhes_despesas' => $data]);
            
        } catch (Exception $e) {
            return response()->json(['error', $e->getMessage(), 'status' => $e->status]);
        }
    }
    

    public function filterTable(Request $request)
    {
        $data = DespesasModel::with(['formaPagamento', 'fornecedor'])
        ->get();

        return response()->json(['Filter' => $request->all()]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */

     public function store(DespesaRequest $request)
    {
        try 
        {
            $despesaDt = new DespesaDetalheController();

            $dataRequest = $request->all();
            
            $despesa = DespesasModel::create($dataRequest);
            $detalhes = $despesaDt->storeDespesasDetalhes($request);
            
            $detalhes = $despesa->detalhes()->createMany($detalhes);
        
            return response()->json(['Despesa_main' => $dataRequest, 'detalhes' => $detalhes]);
            
        } catch (Exception $e) 
        {
            return $e->getMessage();
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
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $despesa = DespesasModel::find($id);
        
        if(!isset($despesa) || empty($despesa) || is_null($despesa))
        {
            return response()->json(['erro' => 'Despesa não foi encontrada']);
        }
        
        $despesa->delete();

        return response()->json(['despesa_deletar' => $despesa]);
    }
}
