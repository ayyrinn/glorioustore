<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrderDetails extends Model
{
    use HasFactory;

    protected $table = 'orderdetails';

    protected $fillable = [
        'orderid',
        'productid',
        'qty',
        'buying_price',
        'subtotal',
    ];

    protected $with = ['product'];

    public function product()
    {
        return $this->belongsTo(Product::class, 'productid', 'productid');
    }
    
}
