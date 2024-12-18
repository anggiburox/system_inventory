<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Stock;
use App\Models\Product;
use App\Models\Warehouses;
use Illuminate\Support\Facades\DB;


class StockController extends Controller
{

    // public function index(Request $request){
    //     // $stock = Stock::all();

    //     // return response()->json(['data' => $stock]);
    //       // Ambil parameter pencarian
    //       $search = $request->get('search');

    //       // Lakukan pencarian berdasarkan query
    //       if ($search) {
    //           $stock = Stock::where('stock_id', 'like', '%' . $search . '%')->paginate(10); // 10 data per halaman
    //       } else {
    //           $stock = Stock::paginate(10); // 10 dataper halaman jika tidak ada pencarian
    //       }
  
    //       return response()->json([
    //           'data' => $stock->items(), // Mengambil data
    //           'pagination' => [
    //               'total' => $stock->total(), // Total data
    //               'current_page' => $stock->currentPage(), // Halaman saat ini
    //               'last_page' => $stock->lastPage(), // Halaman terakhir
    //           ]
    //       ]);
    // }

    public function getAllStocks(Request $request) {
    //     // Mengambil semua stok beserta data produk yang terkait menggunakan eager loading
    // $stocks = Stock::with('product')->get();

    //     // Mengembalikan stok dalam format JSON
    //     return response()->json($stocks);

    // Ambil parameter pencarian
    $search = $request->get('search');
    
    // Lakukan pencarian berdasarkan query
    $stockQuery = Stock::with(['warehouse', 'product']); //  relasi dimuat

    if ($search) {
        $stockQuery->where(function ($query) use ($search) {
            $query->where('stock_id', 'like', '%' . $search . '%')
            ->orWhereHas('product', function ($query) use ($search) {
                $query->where('name', 'like', '%' . $search . '%');
            })
            ->orWhereHas('warehouse', function ($query) use ($search) {
                $query->where('name', 'like', '%' . $search . '%');
            });
        });
    }

    $stock = $stockQuery->paginate(10); // 10 data per halaman

    // Mapping data secara manual
    $mappedData = [];
    foreach ($stock->items() as $item) {
        $mappedData[] = [
            'stock_id' => $item->stock_id,
            'warehouse_name' => $item->warehouse ? $item->warehouse->name : 'Unknown', // Pastikan warehouse ada
            'product_name' => $item->product ? $item->product->name : 'Unknown',     // Pastikan product ada
            'qty' => $item->qty
        ];
    }

    // Mengembalikan data beserta informasi pagination
    return response()->json([
        'data' => $mappedData,
        'pagination' => [
            'total' => $stock->total(), // Total data
            'current_page' => $stock->currentPage(), // Halaman saat ini
            'last_page' => $stock->lastPage(), // Halaman terakhir
        ]
    ]);
    }

    
    public function index(Request $request)
    {
        // Ambil parameter pencarian
        $search = $request->get('search');
    
        // Lakukan pencarian berdasarkan query
        $stockQuery = Stock::with(['warehouse', 'product']); //  relasi dimuat
    
        if ($search) {
            $stockQuery->where(function ($query) use ($search) {
                $query->where('stock_id', 'like', '%' . $search . '%')
                ->orWhereHas('product', function ($query) use ($search) {
                    $query->where('name', 'like', '%' . $search . '%');
                })
                ->orWhereHas('warehouse', function ($query) use ($search) {
                    $query->where('name', 'like', '%' . $search . '%');
                });
            });
        }
    
        $stock = $stockQuery->paginate(10); // 10 data per halaman
    
        // Mapping data secara manual
        $mappedData = [];
        foreach ($stock->items() as $item) {
            $mappedData[] = [
                'stock_id' => $item->stock_id,
                'warehouse_name' => $item->warehouse ? $item->warehouse->name : 'Unknown', // Pastikan warehouse ada
                'product_name' => $item->product ? $item->product->name : 'Unknown',     // Pastikan product ada
                'qty' => $item->qty
            ];
        }
    
        // Mengembalikan data beserta informasi pagination
        return response()->json([
            'data' => $mappedData,
            'pagination' => [
                'total' => $stock->total(), // Total data
                'current_page' => $stock->currentPage(), // Halaman saat ini
                'last_page' => $stock->lastPage(), // Halaman terakhir
            ]
        ]);
    }

    public function getWarehousesByStock(Request $request)
{
    try {
        $warehouses = DB::table('warehouses')
            ->join('stocks', 'warehouses.warehouse_id', '=', 'stocks.warehouse_id')
            ->select('warehouses.warehouse_id', 'warehouses.name')
            ->distinct() 
            ->get();

        return response()->json($warehouses);
    } catch (\Exception $e) {
        return response()->json(['error' => $e->getMessage()], 500);
    }
}


 // Mengambil data produk berdasarkan warehouse_id
 public function getProductByWarehouse(Request $request)
{
    // Validasi input warehouse_id
    $request->validate([
        'warehouse_id' => 'required|exists:warehouses,warehouse_id'
    ]);

    // Ambil data stok beserta relasi produk
    $products = Stock::where('warehouse_id', $request->warehouse_id)
        ->with('product') // Relasi ke produk
        ->get();

    // Format respons jika data kosong
    if ($products->isEmpty()) {
        return response()->json(['message' => 'Tidak ada produk di gudang ini.'], 404);
    }

    // Hilangkan produk duplikat berdasarkan ID produk
    $response = $products
        ->map(function ($stock) {
            return [
                'product_id' => $stock->product->product_id,
                'product_name' => $stock->product->name, // Nama produk dari relasi
                'qty' => $stock->qty, // Jumlah stok
            ];
        })
        ->unique('product_id') // Hapus duplikat berdasarkan product_id
        ->values(); // Reset indeks koleksi

    return response()->json($response);
}


 
public function getProductByStock(Request $request)
{
    try {
        // Validasi input untuk memastikan warehouse_id dikirim
        $request->validate([
            'warehouse_id' => 'required|exists:warehouses,warehouse_id',
        ]);

        // Ambil produk berdasarkan warehouse_id
        $products = DB::table('products')
            ->join('stocks', 'products.product_id', '=', 'stocks.product_id')
            ->where('stocks.warehouse_id', $request->warehouse_id)
            ->select('products.product_id', 'products.name') // Ambil hanya kolom yang dibutuhkan
            ->distinct() // Hindari duplikat data
            ->get();

        return response()->json($products);
    } catch (\Exception $e) {
        return response()->json(['error' => $e->getMessage()], 500);
    }
}


    /**
     * Mendapatkan daftar stok berdasarkan ID gudang.
     * 
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */

public function getStockByWarehouse(Request $request)
{
    try {
        // Validasi input
        $request->validate([
            'warehouse_id' => 'required|exists:warehouses,warehouse_id',
        ], [
            'warehouse_id.exists' => 'Gudang tidak ditemukan.',
        ]);

        // Query stok berdasarkan warehouse_id
        $stocks = Stock::where('warehouse_id', $request->warehouse_id)
            ->with('product') // Relasi ke tabel produk
            ->get();

        // Cek jika stok kosong
        if ($stocks->isEmpty()) {
            return response()->json(['message' => 'Tidak ada stok di gudang ini.'], 404);
        }

        // Format respons JSON
        $response = $stocks->map(function ($stock) {
            return [
                'product_id' => $stock->product_id,
                'warehouse_name' => $stock->warehouse->name,
                'product_name' => $stock->product->name,
                'qty' => $stock->qty,
            ];
        });

        return response()->json($response);
    } catch (\Exception $e) {
        return response()->json(['error' => $e->getMessage()], 500);
    }
}

public function getStockByWarehouseVue(Request $request)
{
    $warehouseId = $request->query('warehouse_id');

    // Validasi jika warehouse_id tidak dikirim
    if (!$warehouseId) {
        return response()->json(['error' => 'Warehouse ID is required'], 400);
    }

    // Query stok berdasarkan warehouse_id
    $stocks = Stock::where('warehouse_id', $warehouseId)
        ->with('product') // Relasi ke tabel produk
        ->get()
        ->map(function ($stock) {
            return [
                'product_id' => $stock->product->id,
                'product_name' => $stock->product->name,
                'qty' => $stock->qty,
            ];
        });

    return response()->json($stocks);
}




    public function store(Request $request){
        $request->validate([
            'warehouse_id' => 'required|exists:warehouses,warehouse_id',  
            'product_id' => 'required|exists:products,product_id',  
            'qty' => 'required|numeric',
        ], [
            'warehouse_id.exists' => 'Warehouse tidak tersedia.',
            'product_id.exists' => 'Produk tidak tersedia.',
        ]);

        $stock = Stock::create($request->all());

        return response()->json(['data' => $stock]);
    }

    // public function update(Request $request, $id){
    //     $request->validate([
    //         'warehouse_id' => 'required|exists:stock,warehouse_id',  
    //         'product_id' => 'required|exists:products,product_id',  
    //         'qty' => 'required|numeric',
    //     ], [
    //         'warehouse_id.exists' => 'Warehouse tidak tersedia.',
    //         'product_id.exists' => 'Produk tidak tersedia.',
    //     ]);

    //     $stock = Stock::findOrFail($id);
    //     $stock->update($request->all());

    //     return response()->json(['data' => $stock]);
    // }
}