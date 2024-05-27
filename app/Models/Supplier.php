<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Kyslik\ColumnSortable\Sortable;

class Supplier extends Model
{
    use HasFactory, Sortable;

    public $incrementing = false;
    protected $primaryKey = 'supplierid';
    protected $keyType = 'string';

    protected $fillable = [
        'supplierid',
        'supname',
        'supaddress',
        'supnumber',
    ];

    protected $sortable = [
        'supname',
        'supaddress',
        'supnumber',
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            if (empty($model->supplierid)) {
                $model->supplierid = static::generateSupplierId();
            }
        });
    }

    public function scopeFilter($query, array $filters)
    {
        $query->when($filters['search'] ?? false, function ($query, $search) {
            return $query->where('name', 'like', '%' . $search . '%');
        });
    }

    public static function generateSupplierId()
    {
        $lastSupplier = static::latest('supplierid')->first();
        if (!$lastSupplier) {
            $number = 1;
        } else {
            $number = intval(substr($lastSupplier->supplierid, 2)) + 1;
        }

        return 'SP' . str_pad($number, 5, '0', STR_PAD_LEFT);
    }
}
