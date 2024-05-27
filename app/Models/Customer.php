<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Kyslik\ColumnSortable\Sortable;

class Customer extends Model
{
    use HasFactory, Sortable;

    public $incrementing = false;
    protected $primaryKey = 'customerid';
    protected $keyType = 'string';

    protected $fillable = [
        'customerid',
        'custname',
        'custemail',
        'custaddress',
        'custnum',
        'custgender',
        'points',
    ];

    public $sortable = [
        'custname',
        'custaddress',
        'custnum',
        'custgender',
        'points',
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            if (empty($model->customerid)) {
                $model->customerid = static::generateCustomerId();
            }
        });
    }

    public function scopeFilter($query, array $filters)
    {
        $query->when($filters['search'] ?? false, function ($query, $search) {
            return $query->where('name', 'like', '%' . $search . '%');
        });
    }

    public static function generateCustomerId()
    {
        $lastCustomer = static::latest('customerid')->first();
        if (!$lastCustomer) {
            $number = 1;
        } else {
            $number = intval(substr($lastCustomer->customerid, 2)) + 1;
        }

        return 'CU' . str_pad($number, 5, '0', STR_PAD_LEFT);
    }
}
