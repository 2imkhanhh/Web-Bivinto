<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StockHistory extends Model
{
    protected $fillable = [
        'product_size_id',
        'type',
        'quantity',
        'stock_before',
        'stock_after',
        'note',
        'user_id',
    ];

    public function productSize()
    {
        return $this->belongsTo(ProductSize::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
