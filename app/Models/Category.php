<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Kyslik\ColumnSortable\Sortable;

class Category extends Model
{
    use HasFactory, Sortable;

    public $incrementing = false;
    protected $primaryKey = 'categoryid';
    protected $keyType = 'string';

    protected $fillable = [
        'categoryid',
        'name',
    ];

    protected $sortable = [
        'name',
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            if (empty($model->categoryid)) {
                $model->categoryid = static::generateCategoryId();
            }
        });
    }

    public function scopeFilter($query, array $filters)
    {
        $query->when($filters['search'] ?? false, function ($query, $search) {
            return $query->where('name', 'like', '%' . $search . '%');
        });
    }

    public static function generateCategoryId()
    {
        $lastCategory = static::latest('categoryid')->first();
        if (!$lastCategory) {
            $number = 1;
        } else {
            $number = intval(substr($lastCategory->categoryid, 2)) + 1;
        }

        return 'TY' . str_pad($number, 5, '0', STR_PAD_LEFT);
    }
}
