<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Warehouses extends Model
{
    use HasFactory;
    
    protected $primaryKey = 'warehouse_id'; 
    
    protected $table='warehouses';  

    protected $fillable = ['name'];
    
    // Relasi dengan tabel products
    public function products()
    {
        return $this->hasMany(Product::class);
    }

}