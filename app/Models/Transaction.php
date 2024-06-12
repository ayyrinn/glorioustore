<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Kyslik\ColumnSortable\Sortable;

class Transaction extends Model
{
    use HasFactory, Sortable;

    public $incrementing = false;
    protected $primaryKey = 'transactionid';
    protected $keyType = 'string';

    protected $fillable = [
        'transactionid',
        'customerid',
        'date',
        'employeeid',
        'payment',
        'type',
        'total',
    ];

    public $sortable = [
        'customerid',
        'employeeid',
        'date',
        'payment',
        'type',
        'total',
    ];

    protected $with = [
        'customer',
        'employee',
    ];

    public function customer()
    {
        return $this->belongsTo(Customer::class, 'customerid');
    }

    public function employee()
    {
        return $this->belongsTo(Employee::class, 'employeeid');
    }

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            if (empty($model->transactionid)) {
                $model->transactionid = static::generateTransactionId();
            }
        });
    }

    public static function generateTransactionId()
    {
        $lastTransaction = static::latest('transactionid')->first();
        if (!$lastTransaction) {
            $number = 1;
        } else {
            $number = intval(substr($lastTransaction->transactionid, 2)) + 1;
        }

        return 'TR' . str_pad($number, 10, '0', STR_PAD_LEFT);
    }
}
