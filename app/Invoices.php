<?php

namespace App;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Laravel\Scout\Searchable;
use Carbon\Carbon;

class Invoices extends Model
{
    protected $table = 'invoices';

    public $timestamps = false;

    protected $connection = 'mysql';
    public function lines()
    {
        return \App\InvoiceLines::where('invoices_id',$this->id)->get();
    }
    public function client()
    {
        return \App\Clients::where('id',$this->clients_id)->first();
    }
    public function timecards()
    {
        return \App\TimeCards::where('invoices_id',$this->id)->get();
    }
}
