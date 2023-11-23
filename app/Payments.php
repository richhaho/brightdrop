<?php

namespace App;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Laravel\Scout\Searchable;
use Carbon\Carbon;

class Payments extends Model
{
    protected $table = 'payments';

    public $timestamps = false;

    protected $connection = 'mysql';
    public function lines()
    {
        return \App\PaymentLines::where('payments_id',$this->id)->get();
    }
    public function worker()
    {
        return \App\Workers::where('id',$this->workers_id)->first();
    }
    public function client()
    {
        return \App\Clients::where('id',$this->clients_id)->first();
    }

    public function timecards()
    {
        return \App\TimeCards::where('payments_id',$this->id)->get();
    }
    public function timecard()
    {
        return \App\TimeCards::where('payments_id',$this->id)->first();
    }
    
}
