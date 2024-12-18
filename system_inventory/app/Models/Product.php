<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;
    
    protected $primaryKey = 'product_id'; 
    
    protected $table='products';  

    protected $fillable = ['name'];


     // Relasi dengan tabel transactions
    public function transactions()
    {
        return $this->hasMany(Transactions::class);
    }
}