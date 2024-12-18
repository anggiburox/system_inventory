<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Warehouses;
use App\Models\Stock;
use App\Models\Transaksi;
use Illuminate\Http\Request;

use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function getData()
{
    try {
        $warehouseCount = Warehouses::count();
        $productCount = Product::count();
        $stockCount = Stock::count();
        $TransaksiCount = Transaksi::count();

        return response()->json([
            'warehouseCount' => $warehouseCount,
            'productCount' => $productCount,
            'stockCount' => $stockCount,
            'TransaksiCount' => $TransaksiCount,
        ]);
    } catch (\Exception $e) {
        return response()->json([
            'error' => $e->getMessage()
        ], 500);
    }
}

}