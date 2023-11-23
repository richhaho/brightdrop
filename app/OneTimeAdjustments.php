<?php

namespace App;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Laravel\Scout\Searchable;
use Carbon\Carbon;


class OneTimeAdjustments extends Model
{
    //use SoftDeletes;
    protected $table = 'onetime_adjustments';

    public $timestamps = false;

    protected $connection = 'mysql';
    public function worker()
    {
        return \App\Workers::where('id',$this->workers_id)->first();
    }
    public function accountmanager()
    {
        return \App\AccountManagers::where('id',$this->account_managers_id)->first();
    }
    public function client()
    {
        return \App\Clients::where('id',$this->workers_id)->first();
    }
    public function reimbursement()
    {
        return \App\Reimbursement::where('onetime_adjustments_id',$this->id)->first();
    }
    public function payment()
    {
        return \App\Payments::where('id',$this->payments_id)->first();
    }
    public function payment_immediate()
    {
        return \App\Payments::where('id',$this->payments_id)->where('payment_type', 'immediate')->first();
    }
}
