<?php

use App\Http\Controllers\CategoriaController;
use App\Http\Controllers\DespesaController;
use App\Http\Controllers\DespesaDetalheController;
use App\Http\Controllers\FormaPagamentoController;
use App\Http\Controllers\FornecedorController;
use App\Http\Controllers\UsuarioController;
use App\Models\CategoriaModel;
use App\Models\FormaPagamentoModel;
use App\Models\FornecedorModel;
use App\Models\UsuarioModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

Route::get('teste', function()
{
   echo 'Óla mundo';

});

Route::get('despesas/{id}', [DespesaController::class, 'index']);
Route::get('despesas/dashboard/limite-despesas/{id}', [DespesaController::class, 'dashboardLimiteDespesas']);
Route::get('despesas/detalhes', [DespesaDetalheController::class, 'index']);
Route::get('despesas/detalhes/{id}', [DespesaDetalheController::class, 'indexSingleDetail']);
Route::get('despesas/main/{id}', [DespesaController::class, 'despesasInfo']);
Route::get('despesas/formas-pagamento/{id}', [FormaPagamentoController::class, 'index']);
Route::get('despesas/fornecedores/{id}', [FornecedorController::class, 'index']);
Route::get('despesas/fornecedores/cnpj/{documento}', [FornecedorController::class, 'searchCnpjApi']);
Route::get('despesas/categoria/{id}', [CategoriaController::class, 'index']);
Route::get('despesas/detalhes/realizar-pagamento/{id}', [DespesaDetalheController::class, 'realizarPagamento']);
Route::get('user/{id}', [UsuarioController::class, 'index']);

Route::post('create-despesa', [DespesaController::class, 'store']);
Route::post('despesa/filtro', [DespesaController::class, 'filterTable']);
Route::post('create-despesa/detalhes', [DespesaDetalheController::class, 'storeDespesasDetalhes']);
Route::post('despesa/create-forma-pagamento', [FormaPagamentoController::class, 'store']);
Route::post('despesa/create-fornecedor', [FornecedorController::class, 'store']);
Route::post('despesa/create-categoria', [CategoriaController::class, 'store']);
Route::post('create-usuario', [UsuarioController::class, 'store']);
Route::post('user/confirm-login', [UsuarioController::class, 'login']);
Route::post('dashboard/filter/{id}', [DespesaController::class, 'filterDashboard']);
Route::post('dashboard/filter-line/{id}', [DespesaController::class, 'filterDashboardLine']);
Route::post('despesas-filter/{id}', [DespesaController::class, 'filterDespesaMain']);

Route::delete('despesas/delete/{id}', [DespesaController::class, 'destroy']);
Route::delete('user/delete/{id}', [UsuarioController::class, 'destroy']);
Route::delete('fornecedor/delete/{id}', [FornecedorController::class, 'destroy']);
Route::delete('forma-pagamento/delete/{id}', [FormaPagamentoController::class, 'destroy']);
Route::delete('categoria/delete/{id}', [CategoriaController::class, 'destroy']);

Route::put('despesas/update/{id}', [DespesaDetalheController::class, 'update']);
Route::put('despesa/update-categoria/{id}', [CategoriaController::class, 'update']);
Route::put('despesa/update-forma-pagamento/{id}', [FormaPagamentoController::class, 'update']);
Route::put('despesa/update-fornecedor/{id}', [FornecedorController::class, 'update']);
Route::put('user/update-user/{id}', [UsuarioController::class, 'update']);

