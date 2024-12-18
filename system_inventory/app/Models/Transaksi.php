<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

use App\Models\Warehouses;
use App\Models\Product;
use App\Models\User;

class Transaksi extends Model
{
    use HasFactory;
    protected $table = 'transactions';
    
    protected $primaryKey = 'transaction_id';
    public $incrementing = false;  
    

    protected $fillable = ['transaction_id','transaction_type', 'warehouse_id', 'product_id','user_id','qty_transactions'];


//     // Fungsi untuk membuat ID custom
//     public static function generateCustomID()
//     {
//         // Ambil transaksi terakhir dan parse ID untuk mendapatkan urutan angka terakhir
//         $lastTransaction = self::orderBy('transaction_id', 'desc')->first();
//         $lastTransactionId = $lastTransaction ? (int) substr($lastTransaction->transaction_id, 3) : 0; // Ambil angka terakhir dari ID
//         $newTransactionId = str_pad($lastTransactionId + 1, 3, '0', STR_PAD_LEFT); // Buat ID baru
//         return 'IT-' . $newTransactionId;
//     }

//     public function warehouse()
//     {
//         return $this->belongsTo(Warehouses::class, 'warehouse_id');
//     }

//     public function product()
//     {
//         return $this->belongsTo(Product::class, 'product_id');
//     }
    
//   // Relasi dengan user
//   public function user()
//   {
//       return $this->belongsTo(User::class, 'user_id');
//   }

}