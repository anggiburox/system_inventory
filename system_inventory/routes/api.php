<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\StockController;
use App\Http\Controllers\WarehousesController;
use App\Http\Controllers\TransactionsController;
use App\Http\Controllers\DashboardController;


/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

// Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//     return $request->user();
// });

Route::prefix('auth')->group(function () {
    Route::post('/login', [AuthController::class, 'login']);
    Route::post('/register', [AuthController::class, 'register']);
    Route::get('/logout', [AuthController::class, 'logout'])->middleware('auth:sanctum');
    Route::get('/me', [AuthController::class, 'me'])->middleware('auth:sanctum');
});


Route::middleware('auth:sanctum')->group(function () {

    Route::get('/dashboard-data', [DashboardController::class, 'getData']);

    Route::get('/user', [UserController::class, 'index']);
    Route::post('/user', [UserController::class, 'store']);
    Route::patch('/user/{id}', [UserController::class, 'update']);
    Route::delete('/user/{id}', [UserController::class, 'destroy']);

    Route::get('/products', [ProductController::class, 'index']);
    Route::post('/products', [ProductController::class, 'store']);
    Route::put('/products/{id}', [ProductController::class, 'update']);
    Route::get('/products/{id}', [ProductController::class, 'show']);
    Route::delete('/products/{id}', [ProductController::class, 'destroy']);

    Route::get('/warehouses', [WarehousesController::class, 'index']);
    Route::get('/warehousesvue', [WarehousesController::class, 'warehousesvue']);
    Route::post('/warehouses', [WarehousesController::class, 'store']);
    Route::put('/warehouses/{id}', [WarehousesController::class, 'update']);
    Route::get('/warehouses/{id}', [WarehousesController::class, 'show']);
    Route::delete('/warehouses/{id}', [WarehousesController::class, 'destroy']);
    // Route::get('/warehouses/forstock', [WarehousesController::class, 'getWarehousesForStock']);

    // Route::get('/stock', [StockController::class, 'index']);
    Route::get('/stockgetAll', [StockController::class, 'getAllStocks']);
    Route::get('/stocks', [StockController::class, 'getStockByWarehouse']);
    Route::post('/stock', [StockController::class, 'store']);
    Route::get('/warehouses-by-stock', [StockController::class, 'getWarehousesByStock']);
    Route::get('/products-by-warehouse', [StockController::class, 'getProductByWarehouse']);
    // Route::get('/stock-by-warehouse', [StockController::class, 'getStockByWarehouseVue']);
    // Route::put('/stock/{id}', [StockController::class, 'update']);

    
    Route::get('/transactions', [TransactionsController::class, 'index']);
    Route::post('/transactions', [TransactionsController::class, 'store']);
    Route::get('/transactions/latest', [TransactionsController::class, 'getLatestTransaction']);
    // Route::put('/transactions/{id}', [TransactionsController::class, 'update']);
    // Route::delete('/transactions/{id}', [TransactionsController::class, 'destroy']);


});