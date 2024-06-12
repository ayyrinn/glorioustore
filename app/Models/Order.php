<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Kyslik\ColumnSortable\Sortable;

class Order extends Model
{
    use HasFactory, Sortable;

    public $incrementing = false;
    protected $primaryKey = 'orderid';
    protected $keyType = 'string';

    protected $fillable = [
        'orderid',
        'supplierid',
        'date',
        'status',
    ];

    public $sortable = [
        'date',
        'status',
    ];
    
    public function orderdetails()
    {
        return $this->hasMany(OrderDetails::class, 'orderid', 'orderid');
    }


    public function supplier()
    {
        return $this->belongsTo(Supplier::class, 'supplierid', 'supplierid');
    }

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            if (empty($model->orderid)) {
                $model->orderid = static::generateOrderId();
            }
        });
    }

    public function scopeFilter($query, array $filters)
    {
        $query->when($filters['search'] ?? false, function ($query, $search) {
            return $query->whereHas('orderdetails.product', function ($query) use ($search) {
                $query->where('productname', 'like', '%' . $search . '%');
            });
        });
    }


    public static function generateOrderId()
    {
        $lastOrder = static::latest('orderid')->first();
        if (!$lastOrder) {
            $number = 1;
        } else {
            $number = intval(substr($lastOrder->orderid, 2)) + 1;
        }

        return 'OR' . str_pad($number, 7, '0', STR_PAD_LEFT);
    }
}
