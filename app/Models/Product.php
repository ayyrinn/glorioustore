<?php

namespace App\Models;

use Kyslik\ColumnSortable\Sortable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Product extends Model
{
    use HasFactory, Sortable;

    protected $fillable = [
        'productname',
        'category_id',
        'supplier_id',
        'productid',
        'stock',
        'product_image',
        'price',
        'description',
    ];

    public $sortable = [
        'name',
        'prices',
        'stocks',
    ];

    protected $guarded = [
        'id',
    ];

    protected $with = [
        'category',
        'supplier'
    ];

    public function category(){
        return $this->belongsTo(Category::class, 'category_id');
    }

    public function supplier(){
        return $this->belongsTo(Supplier::class, 'supplier_id');
    }

    public function scopeFilter($query, array $filters)
    {
        $query->when($filters['search'] ?? false, function ($query, $search) {
            return $query->where('product_name', 'like', '%' . $search . '%');
        });
    }
}
