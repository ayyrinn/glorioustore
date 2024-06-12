<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Kyslik\ColumnSortable\Sortable;

class Attendence extends Model
{
    use HasFactory, Sortable;

    protected $fillable = [
        'employeeid',
        'date',
        'status',
    ];

    public $sortable = [
        'employeeid',
        'date',
        'status',
    ];

    protected $guarded = [
        'id'
    ];

    protected $with = [
        'employee',
    ];

    public function employee(){
        return $this->belongsTo(Employee::class, 'employeeid', 'employeeid');
    }

    public function getRouteKeyName()
    {
        return 'date';
    }
}
