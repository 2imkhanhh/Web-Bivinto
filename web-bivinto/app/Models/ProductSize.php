<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProductSize extends Model
{
    protected $fillable = [
        'product_color_id',
        'size_name',
        'stock'
    ];

    public function color()
    {
        return $this->belongsTo(ProductColor::class, 'product_color_id');
    }

    public function stockHistories()
    {
        return $this->hasMany(StockHistory::class);
    }
}
