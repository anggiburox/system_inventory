<?php

namespace App\Http\Controllers;

use App\Models\Warehouses;
use Illuminate\Http\Request;


class WarehousesController extends Controller
{

    public function warehousesvue(){
        $warehouses = Warehouses::select('warehouse_id', 'name')->get();

        return response()->json([$warehouses]);
    }

    public function index(Request $request)
    {
        // Ambil parameter pencarian
        $search = $request->get('search');

        // Lakukan pencarian berdasarkan query
        if ($search) {
            $warehouses = Warehouses::where('name', 'like', '%' . $search . '%')->paginate(10); // 10 data per halaman
        } else {
            $warehouses = Warehouses::paginate(10); // 10 dataper halaman jika tidak ada pencarian
        }

        return response()->json([
            'data' => $warehouses->items(), // Mengambil data
            'pagination' => [
                'total' => $warehouses->total(), // Total data
                'current_page' => $warehouses->currentPage(), // Halaman saat ini
                'last_page' => $warehouses->lastPage(), // Halaman terakhir
            ]
        ]);
    }
    
    // public function getWarehousesForStock(Request $request)
    // {
    //     // Ambil parameter pencarian jika ada
    //     $search = $request->get('search');
    
    //     // Query untuk mengambil warehouse yang memiliki stok
    //     $warehousesQuery = Warehouses::whereHas('stocks');
    
    //     // Jika ada pencarian berdasarkan nama warehouse
    //     if ($search) {
    //         $warehousesQuery->where('name', 'like', '%' . $search . '%');
    //     }
    
    //     // Ambil data warehouse yang memiliki stok
    //     $warehouses = $warehousesQuery->get();
    
    //     // Log query SQL untuk debugging
    //     \Log::info('Warehouses Query: ' . $warehousesQuery->toSql());
    
    //     // Jika tidak ada data
    //     if ($warehouses->isEmpty()) {
    //         return response()->json([
    //             'message' => 'Data tidak ditemukan.'
    //         ], 404); // Menambahkan kode status 404 jika tidak ada data
    //     }
    
    //     return response()->json([
    //         'data' => $warehouses
    //     ]);
    // }
    
    


    public function store(Request $request){
        $request->validate([
            'name' => 'required|string|max:255',
        ]);

        $warehouses = Warehouses::create($request->all());

        return response()->json(['data' => $warehouses]);
    }

    public function update(Request $request, $id){
        $request->validate([
            'name' => 'required|string|max:255',
        ]);

        $warehouses = Warehouses::findOrFail($id);
        $warehouses->update($request->all());

        return response()->json(['data' => $warehouses]);
    }

    public function show($id)
    {
        // Coba cari data berdasarkan ID
        $warehouse = Warehouses::find($id);
    
        if (!$warehouse) {
            return response()->json(['message' => 'Data tidak ditemukan.'], 404);
        }
    
        return response()->json(['data' => $warehouse]);
    }

    public function destroy($id){
        // Mencari data berdasarkan ID
        $warehouses = Warehouses::find($id);
    
        if(!$warehouses){
            return response()->json(['message' => 'Warehouses tidak ditemukan.'], 404);
        }
    
        // Menghapus data
        $warehouses->delete();
    
        // Mengembalikan respons sukses
        return response()->json(['message' => 'Warehouses berhasil dihapus.']);
    }
}