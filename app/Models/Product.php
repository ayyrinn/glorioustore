<?php

namespace App\Models;

use Kyslik\ColumnSortable\Sortable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Models\TransactionDetails;

class Product extends Model
{
    use HasFactory, Sortable;

    public $incrementing = false;
    protected $primaryKey = 'productid';
    protected $keyType = 'string';

    protected $fillable = [
        'productid',
        'productname',
        'categoryid',
        'productid',
        'stock',
        'product_image',
        'price',
        'description',
    ];

    public $sortable = [
        'name',
        'price',
        'stock',
    ];

    protected $with = [
        'category',
    ];

    public function category(){
        return $this->belongsTo(Category::class, 'categoryid');
    }

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            if (empty($model->productid)) {
                $model->productid = static::generateProductId();
            }
        });
    }

    public function scopeFilter($query, array $filters)
    {
        $query->when($filters['search'] ?? false, function ($query, $search) {
            return $query->where('productname', 'like', '%' . $search . '%');
        });
    }

    public static function generateProductId()
    {
        $lastProduct = static::latest('productid')->first();
        if (!$lastProduct) {
            $number = 1;
        } else {
            $number = intval(substr($lastProduct->productid, 2)) + 1;
        }

        return 'PR' . str_pad($number, 7, '0', STR_PAD_LEFT);
    }

    public function transactionDetails()
    {
        return $this->hasMany(TransactionDetails::class, 'productid', 'productid');
    }

    public function scopeTopSold($query, $period)
    {
        return $query->withCount(['transactionDetails as quantity_sold' => function ($query) use ($period) {
            $query->join('transactions', 'transaction_details.transaction_id', '=', 'transactions.id')
                ->when($period == 'year', function ($query) {
                    $query->selectRaw('YEAR(transactions.date) as year');
                })
                ->when($period == 'month', function ($query) {
                    $query->selectRaw('YEAR(transactions.date) as year, MONTH(transactions.date) as month');
                })
                ->when($period == 'week', function ($query) {
                    $query->selectRaw('YEAR(transactions.date) as year, WEEK(transactions.date) as week');
                })
                ->groupBy('product_id');
        }]);
    }
}
