<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TransactionDetails extends Model
{
    use HasFactory;

    protected $table = 'transactiondetails';

    protected $fillable = [
        'transactionid',
        'productid',
        'quantity',
        'price',
        'total',
    ];

    protected $with = ['product'];

    public function product()
    {
        return $this->belongsTo(Product::class, 'productid', 'productid');
    }
}
