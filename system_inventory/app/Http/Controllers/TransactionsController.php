<?php

namespace App\Http\Controllers;

use App\Models\Transactions;
use App\Models\Stock;
use App\Models\Warehouse;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;




class TransactionsController extends Controller
{

    public function index(Request $request)
{
    $search = $request->get('search');

    $transactionsQuery = Transactions::with(['warehouse', 'product']); // Ganti 'warehouses' dengan 'warehouse'

    if ($search) {
        $transactionsQuery->where(function ($query) use ($search) {
            $query->where('transaction_id', 'like', '%' . $search . '%')
                ->orWhereHas('product', function ($query) use ($search) {
                    $query->where('name', 'like', '%' . $search . '%');
                })
                ->orWhereHas('warehouse', function ($query) use ($search) {
                    $query->where('name', 'like', '%' . $search . '%');
                });
        });
    }

    $transactions = $transactionsQuery->paginate(10); // 10 data per halaman

    // Mapping data secara manual
     $mappedData = [];
     foreach ($transactions->items() as $item) {
         $mappedData[] = [
             'transaction_id' => $item->transaction_id,
             'transaction_type' => $item->transaction_type,
             'warehouse_name' => $item->warehouse ? $item->warehouse->name : 'Unknown', // Pastikan warehouse ada
             'product_name' => $item->product ? $item->product->name : 'Unknown',     // Pastikan product ada
             'user_name' => $item->user ? $item->user->name : 'Unknown',     // Pastikan product ada
             'qty_transactions' => $item->qty_transactions,
             'created_at' => $item->created_at->format('d-M-Y')
         ];
     }

    return response()->json([
        'data' => $mappedData,
        'pagination' => [
            'total' => $transactions->total(),
            'current_page' => $transactions->currentPage(),
            'last_page' => $transactions->lastPage(),
        ]
    ]);
}

// custom id
    public function getLatestTransaction()
    {
        try {
            // Gunakan fungsi untuk menghasilkan custom transaction_id
            $newTransactionId = Transactions::generateCustomID();
            
            // Kirimkan ID tersebut dalam response
            return response()->json([
                'data' => ['transaction_id' => $newTransactionId]
            ]);
        } catch (\Exception $e) {
            Log::error('Error generating transaction ID:', ['error' => $e->getMessage()]);
            return response()->json(['message' => 'Failed to generate transaction ID'], 500);
        }
    }

//     public function store(Request $request)
// {
//     // Validasi input
//     $request->validate([
//         'transaction_type' => ['required', 'string', 'in:in,out'],
//         'warehouse_id' => 'required|exists:warehouses,warehouse_id',  
//         'product_id' => 'required|exists:products,product_id',  
//         'user_id' => 'required|exists:users,user_id',  
//         'qty_transactions' => 'required|integer|min:1', // Pastikan kolom qty_transactions sesuai dengan inputan
//     ], [
//         'warehouse_id.exists' => 'Warehouse tidak tersedia.',
//         'product_id.exists' => 'Produk tidak tersedia.',
//         'user_id.exists' => 'User tidak tersedia.',
//         'qty_transactions.min' => 'Jumlah barang (qty) tidak bisa kurang dari 1.',
//     ]);

//     // Mengecek apakah stok ada terlebih dahulu sebelum menyimpan transaksi
//     $stock = Stock::where('warehouse_id', $request->warehouse_id)
//                   ->where('product_id', $request->product_id)
//                   ->first();

//     // Jika stok tidak ditemukan, kembalikan error
//     if (!$stock) {
//         return response()->json(['message' => 'Stock tidak ditemukan.'], 404);
//     }

//     // Jika jenis transaksi 'out' dan stok tidak mencukupi
//     if ($request->transaction_type == 'out' && $stock->qty < $request->qty_transactions) {
//         return response()->json(['message' => 'Stok tidak cukup untuk pengurangan.'], 400); // Error jika stok tidak cukup
//     }

//     // Membuat ID untuk transaction_id, misalnya IT001
//     $transaction_count = Transactions::count(); // Menghitung jumlah transaksi yang sudah ada
//     $transaction_id = 'IT' . str_pad($transaction_count + 1, 3, '0', STR_PAD_LEFT); // Membuat transaction_id

//     // Menambahkan transaction_id ke request data
//     $data = $request->all();
//     $data['transaction_id'] = $transaction_id;

//     // Membuat transaksi baru
//     $transactions = Transactions::create($data);

//     // Melakukan mutasi pada qty di tabel stocks setelah transaksi berhasil disimpan
//     if ($transactions->transaction_type == 'in') {
//         // Jika "in", tambah qty
//         $stock->qty += $request->qty_transactions; // Gunakan qty_transactions
//     } else {
//         // Jika "out", kurangi qty
//         $stock->qty -= $request->qty_transactions; // Gunakan qty_transactions
//     }

//     // Update stok setelah mutasi
//     $stock->save();

//     return response()->json(['data' => $transactions]);
// }
/////////////////////////////////////////////////////////////////////////////////////////////////

// public function store(Request $request)
// {
//     try {
//         // Validasi input
//         $request->validate([
//             'transaction_type' => 'required',
//             'warehouse_id' => 'required',
//             'product_id' => 'required',
//             'qty_transactions' => 'required|numeric|min:1',
//         ]);

//         // Menyimpan transaksi baru
//         $transaction = new Transactions([
//             'transaction_id' => Transactions::generateCustomID(),
//             'transaction_type' => $request->transaction_type,
//             'warehouse_id' => $request->warehouse_id,
//             'product_id' => $request->product_id,
//             'qty_transactions' => $request->qty_transactions,
//             'user_id' => auth()->id(), // Mengambil ID pengguna yang sedang login
//         ]);

//         $transaction->save();

//         return response()->json([
//             'message' => 'Transaksi berhasil disimpan',
//             'data' => $transaction
//         ], 201); // Status code 201 = Created

//     } catch (\Exception $e) {
//         // Menangani error dan memberikan respons yang lebih informatif
//         return response()->json([
//             'message' => 'Gagal menyimpan data transaksi',
//             'error' => $e->getMessage(),
//         ], 500); // Status code 500 = Internal Server Error
//     }
// }


public function store(Request $request)
{
        // Validasi input
    $request->validate([
        'transaction_type' => ['required', 'string', 'in:in,out'],
        'warehouse_id' => 'required|exists:warehouses,warehouse_id',  
        'product_id' => 'required|exists:products,product_id',  
        'qty_transactions' => 'required|integer|min:1', // Pastikan kolom qty_transactions sesuai dengan inputan
    ], [
        'warehouse_id.exists' => 'Warehouse tidak tersedia.',
        'product_id.exists' => 'Produk tidak tersedia.',
        'user_id.exists' => 'User tidak tersedia.',
        'qty_transactions.min' => 'Jumlah barang (qty) tidak bisa kurang dari 1.',
    ]);

        // Menyimpan transaksi baru
        $transaction = new Transactions([
            'transaction_id' => Transactions::generateCustomID(),
            'transaction_type' => $request->transaction_type,
            'warehouse_id' => $request->warehouse_id,
            'product_id' => $request->product_id,
            'qty_transactions' => $request->qty_transactions,
            'user_id' => auth()->id(), // Mengambil ID pengguna yang sedang login
        ]);

        // Menyimpan transaksi
        $transaction->save();

        // Logika untuk menambah atau mengurangi qty pada tabel stock
        $stock = Stock::where('warehouse_id', $request->warehouse_id)
                      ->where('product_id', $request->product_id)
                      ->first();

                      // Jika stok tidak ditemukan, kembalikan error
if (!$stock) {
    return response()->json(['message' => 'Stock tidak ditemukan.'], 404);
}

// Jika jenis transaksi 'out' dan stok tidak mencukupi
if ($request->transaction_type == 'out' && $stock->qty < $request->qty_transactions) {
    return response()->json(['message' => 'Stok tidak cukup untuk pengurangan.'], 400); // Error jika stok tidak cukup
}

        // Melakukan mutasi pada qty di tabel stocks setelah transaksi berhasil disimpan
    if ($transaction->transaction_type == 'in') {
        // Jika "in", tambah qty
        $stock->qty += $request->qty_transactions; // Gunakan qty_transactions
    } else {
        // Jika "out", kurangi qty
        $stock->qty -= $request->qty_transactions; // Gunakan qty_transactions
    }


        // Update stok
        $stock->save();

        return response()->json([
            'message' => 'Transaksi berhasil disimpan dan stok diperbarui',
            'data' => $transaction
        ], 201); // Status code 201 = Created
}


// public function update(Request $request, $id)
// {
//     // Validasi input
//     $request->validate([
//         'transaction_type' => ['required', 'string', 'in:in,out'],
//         'warehouse_id' => 'required|exists:warehouses,warehouse_id',  
//         'product_id' => 'required|exists:products,product_id',  
//         'user_id' => 'required|exists:users,user_id',  
//         'qty_transactions' => 'required|integer|min:1', 
//     ], [
//         'warehouse_id.exists' => 'Warehouse tidak tersedia.',
//         'product_id.exists' => 'Produk tidak tersedia.',
//         'user_id.exists' => 'User tidak tersedia.',
//         'qty_transactions.min' => 'Jumlah barang (qty) tidak bisa kurang dari 1.',
//     ]);

//     // Mencari transaksi yang ingin diupdate
//     $transaction = Transactions::findOrFail($id);

//     // Mengecek apakah stok ada terlebih dahulu sebelum menyimpan perubahan transaksi
//     $stock = Stock::where('warehouse_id', $request->warehouse_id)
//                   ->where('product_id', $request->product_id)
//                   ->first();

//     if (!$stock) {
//         return response()->json(['message' => 'Stock tidak ditemukan.'], 404);
//     }

//     // Jika jenis transaksi 'out' dan stok tidak mencukupi, tampilkan pesan error
//     if ($request->transaction_type == 'out' && $stock->qty < $request->qty_transactions) {
//         return response()->json(['message' => 'Stok tidak cukup untuk pengurangan.'], 400);
//     }

//     // Menyimpan data yang diperbarui
//     $data = $request->all();

//     // Menyimpan perubahan pada transaksi
//     $transaction->update($data);

//     // Menyesuaikan perubahan stok
//     // Jika jenis transaksi 'in', tambahkan qty
//     // Jika jenis transaksi 'out', kurangi qty
//     if ($transaction->transaction_type == 'in') {
//         $stock->qty += $request->qty_transactions; // Menambah qty stok
//     } else {
//         $stock->qty -= $request->qty_transactions; // Mengurangi qty stok
//     }

//     // Update stok setelah perubahan transaksi
//     $stock->save();

//     return response()->json(['data' => $transaction]);
// }

}