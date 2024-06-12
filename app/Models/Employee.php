<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Kyslik\ColumnSortable\Sortable;

class Employee extends Model
{
    use HasFactory, Sortable;

    public $incrementing = false;
    protected $primaryKey = 'employeeid';
    protected $keyType = 'string';

    protected $fillable = [
        'employeeid',
        'name',
        'email',
        'phone',
        'address',
        'gender',
        'DOB',
        'role',
        'photo',
        'salary',
    ];

    public $sortable = [
        'name',
        'email',
        'phone',
        'salary',
        'role'
    ];
    
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            if (empty($model->employeeid)) {
                $model->employeeid = static::generateEmployeeId();
            }
        });
    }

    public function scopeFilter($query, array $filters)
    {
        $query->when($filters['search'] ?? false, function ($query, $search) {
            return $query->where('name', 'like', '%' . $search . '%');
        });
    }

    public function advance_salaries()
    {
        return $this->hasMany(AdvanceSalary::class);
    }

    public static function generateEmployeeId()
    {
        $lastEmployee = static::latest('employeeid')->first();
        if (!$lastEmployee) {
            $number = 1;
        } else {
            $number = intval(substr($lastEmployee->employeeid, 2)) + 1;
        }

        return 'EM' . str_pad($number, 5, '0', STR_PAD_LEFT);
    }
}
