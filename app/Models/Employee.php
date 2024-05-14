<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use Kyslik\ColumnSortable\Sortable;

class Employee extends Model
{
    use HasFactory, Sortable;

    protected $fillable = [
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

    protected $guarded = [
        'id'
    ];

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
}
