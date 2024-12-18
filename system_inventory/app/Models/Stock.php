<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

use App\Models\Warehouses;
use App\Models\Product;


class Stock extends Model
{
    protected $primaryKey = 'stock_id'; 
    
    protected $table='stocks';  

    protected $fillable = ['warehouse_id', 'product_id', 'qty'];

    public function warehouse()
    {
        return $this->belongsTo(Warehouses::class, 'warehouse_id');
    }

    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id');
    }

      // Relasi dengan warehouse
      public function warehouses()
      {
          return $this->belongsTo(Warehouses::class, 'warehouse_id', 'warehouse_id');
      }

}