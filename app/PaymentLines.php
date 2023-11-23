<?php

namespace App;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Laravel\Scout\Searchable;
use Carbon\Carbon;

class PaymentLines extends Model
{
    protected $table = 'payment_lines';

    public $timestamps = false;

    protected $connection = 'mysql';
    
}
