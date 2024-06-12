<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProfitView extends Model
{
    use HasFactory;
    
    protected $table = 'profit_view';
    public $timestamps = false;
}
