<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;


class ProductController extends Controller
{

    public function index(Request $request)
    {
        // Ambil parameter pencarian
        $search = $request->get('search');

        // Lakukan pencarian berdasarkan query
        if ($search) {
            $products = Product::where('name', 'like', '%' . $search . '%')->paginate(10); // 10 produk per halaman
        } else {
            $products = Product::paginate(10); // 10 produk per halaman jika tidak ada pencarian
        }

        return response()->json([
            'data' => $products->items(), // Mengambil data produk
            'pagination' => [
                'total' => $products->total(), // Total produk
                'current_page' => $products->currentPage(), // Halaman saat ini
                'last_page' => $products->lastPage(), // Halaman terakhir
            ]
        ]);
    }

    public function store(Request $request){
        $request->validate([
            'name' => 'required|string|max:255',
        ]);

        $product = Product::create($request->all());

        return response()->json(['data' => $product]);
    }

    public function show($id)
{
    // Coba cari produk berdasarkan ID
    $product = Product::find($id);

    if (!$product) {
        return response()->json(['message' => 'Product tidak ditemukan.'], 404);
    }

    return response()->json(['data' => $product]);
}

    public function update(Request $request, $id){
        $request->validate([
            'name' => 'required|string|max:255',
        ]);

        $product = Product::findOrFail($id);
        $product->update($request->all());

        return response()->json(['data' => $product]);
    }

    public function destroy($id){
    // Mencari produk berdasarkan ID
    $product = Product::find($id);

    if(!$product){
        return response()->json(['message' => 'Product tidak ditemukan.'], 404);
    }

    // Menghapus produk
    $product->delete();

    // Mengembalikan respons sukses
    return response()->json(['message' => 'Product berhasil dihapus.']);
    }

}