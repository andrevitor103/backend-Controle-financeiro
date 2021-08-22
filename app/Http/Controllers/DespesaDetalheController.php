<?php

namespace App\Http\Controllers;

use App\Models\DespesasDetalhesModel;
use App\Models\DespesasModel;
use App\Models\FormaPagamentoModel;
use App\Models\FornecedorModel;
use Illuminate\Http\Request;

class DespesaDetalheController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $data = DespesasDetalhesModel::all();
        
        return response()->json(['data' => $data]);
    }

    public function indexSingleDetail($id)
    {
        $singleDetail = DespesasDetalhesModel::where('id', $id)->with(['despesas'])->get();
        
        if(is_null($singleDetail))
        {
            return response()->json(['Erro' => 'Despesa não encontrada']);
        }

        return response()->json(['detalhes_despesa' => $singleDetail]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Array $request)
    {
        $despesas = $request;
        
        return response()->json(['data' => $despesas]);
    }


    function storeDespesasDetalhes(Request $request)
    {
       $request["VALOR_DESPESA"] = $request["VALOR_DESPESA"] - $request["DESCONTO"];
       
       $detalhes = $request->except( 'ID_CATEGORIA', 'ID_CONDICAOPAGAMENTO', 'JUROS_ATRASO', 'DESCONTO', 'ID_FORMA_PAGAMENTO', 'ID_USUARIO');
       $detalhesNew = [];
       $detalhesNew['DIA_FATURA'] = $this->getInvoiceClosingDay($detalhes['ID_FORNECEDOR'])->DATA_COBRANCA ?? 1;
       $detalhesNew['DATA_VENCIMENTO'] = $detalhes['DATA_COMPRA']; 
       $detalhesNew['VALOR_PARCELA'] =  ($detalhes['VALOR_DESPESA']/$detalhes['TOTAL_PARCELAS']);
       $detalhesNew = array_merge($detalhesNew, $detalhes);
        
       return $this->mountedStoreDespesasDetalhes($detalhesNew);

    //    return response()->json(['despesaDt' => $detalhesNew]);
    }

    function getInvoiceClosingDay($id)
    {
        return FormaPagamentoModel::find($id) ?? 'Óla mundo';
    } 

     function mountedStoreDespesasDetalhes(Array $despesa)
    {
        $dados = [];
        $parcelas = $despesa['TOTAL_PARCELAS'];
        $despesa = $this->removerKeys($despesa, ['TOTAL_PARCELAS', 'DATA_COMPRA', 'VALOR_DESPESA']);
        $despesa = $this->createKeys($despesa, ["DATA_PAGAMENTO" => NULL, "JUROS" => 0, "DESCONTO" => 0]);
        if($parcelas >= 1 )
        {
            for($i = 0; $i < $parcelas; $i++)
            {
              $despesa['DATA_VENCIMENTO'] = strtotime($despesa['DATA_VENCIMENTO']);

              if ($i == 0) {
                  $despesa['DATA_VENCIMENTO'] = $this->verifyDateInitionPagamento($despesa);
              } else {
                $despesa['DATA_VENCIMENTO'] = $this->generateDueDate($despesa['DATA_VENCIMENTO'], 30, $despesa['DIA_FATURA']);
              }
              $despesa['NUMERO_PARCELA'] = $i+1;
              $dados[] = $despesa;
            }
        }
        
        return $dados;
    }

    function verifyDateInitionPagamento($despesa) {
        if ($despesa["DIA_FATURA"] >= date('d', strtotime($despesa['DATA_VENCIMENTO']))) {
                $despesa['DATA_VENCIMENTO'] = $this->generateDueDate($despesa['DATA_VENCIMENTO'], 0, $despesa['DIA_FATURA']);
              } else {
                 $despesa['DATA_VENCIMENTO'] = $this->generateDueDate($despesa['DATA_VENCIMENTO'], 30, $despesa['DIA_FATURA']);
              }
              return $despesa['DATA_VENCIMENTO'];
    }

    function generateDueDate($dayAdd, $dataBase = null, $dayDue)
    {
        $date = date('Y-m-', strtotime("+{$dataBase} days", $dayAdd)).$dayDue;

        return date('Y-m-d', strtotime($date));
    }

    function removerKeys(array $arrayData, array $keysRemove)
    {
        foreach($keysRemove as $key => $value)
        {
            if($arrayData[$value])
            {
                unset($arrayData[$value]);
            }
        }
        return $arrayData;
    }

    function createKeys(array $arrayData, array $keysCreate)
    {
        foreach($keysCreate as $key => $value)
        {
            if(!isset($arrayData[$value]))
            {
                $arrayData[$key] = $value;
            }
        }
        return $arrayData;
    }

    function realizarPagamento(Int $id)
    {
        date_default_timezone_set('America/Sao_Paulo');

        $despesaPagamento = DespesasDetalhesModel::find($id);
    
        if(!isset($despesaPagamento))
        {   
            return response()->json(['erro' => 'despesa não encontrada']);    
        }
        if($despesaPagamento->DATA_PAGAMENTO != null)
        {
            return $this->reativarPagamento($id);
        }
        $despesaPagamento->DATA_PAGAMENTO = date('Y-m-d');
        $despesaPagamento->save();

        return response()->json(['pagamento_realizado' => $despesaPagamento, 'date_now' => date('Y-m-d')]);
    }

    function reativarPagamento(int $id)
    {
        $despesaPagamento = DespesasDetalhesModel::find($id);
        
        $despesaPagamento->DATA_PAGAMENTO = null;
        
        $despesaPagamento->save();

        return response()->json(['pagamento_reativado' => $despesaPagamento, 'date_now' => date('Y-m-d')]);
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
        $despesaAtualizada = $request->all();

        $detalhe = DespesasDetalhesModel::find($id);
        $detalhe->update($despesaAtualizada);

        $despesa = $detalhe->despesas()->first();

        $despesa->update($despesaAtualizada);
        
        
        return response()->json(['Despesa_atualizar' => $detalhe]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
