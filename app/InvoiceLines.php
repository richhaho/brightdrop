<?php

namespace App;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Laravel\Scout\Searchable;
use Carbon\Carbon;

class InvoiceLines extends Model
{
    protected $table = 'invoice_lines';

    public $timestamps = false;

    protected $connection = 'mysql';
    
}
