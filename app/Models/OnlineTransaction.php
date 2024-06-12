<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Kyslik\ColumnSortable\Sortable;

class OnlineTransaction extends Model
{
    use HasFactory, Sortable;

    protected $table = 'onlinetransactions';

    protected $fillable = [
        'transactionid',
        'courierid',
        'status',
        'notes',
        'proofpayment',
        'proofdelivery',
    ];

    public $sortable = [
        'transactionid',
        'courierid',
        'status',
    ];

    protected $with = ['employee', 'transaction'];

    public function employee()
    {
        return $this->belongsTo(Employee::class, 'courierid', 'employeeid');
    }

    public function transaction()
    {
        return $this->belongsTo(Transaction::class, 'transactionid', 'transactionid');
    }
}

