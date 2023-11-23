<?php

namespace App;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Laravel\Scout\Searchable;
use Carbon\Carbon;


class RecurringAdjustments extends Model
{
    //use SoftDeletes;
    protected $table = 'recurring_adjustments';
    protected $fillable = [
        'clients_id',
        'workers_id',
        'account_managers_id',
        'date_submitted',
        'payto',
        'paytoclient',
        'paytoworker',
        'billto',
        'billtoclient',
        'billtoworker',
        'description',
        'amount',
        'currency_type',
        'payment_method',
        'status',
        'handle_date',
        'internal_notes',
        'created_at',
        'deleted_at',
        'updated_at',
        'payroll_managers_id',
        'payments_id',
        'invoices_id'
    ];

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

}
