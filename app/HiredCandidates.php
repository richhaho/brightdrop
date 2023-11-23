<?php
namespace App;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Laravel\Scout\Searchable;
use Carbon\Carbon;

class HiredCandidates extends Model
{
    protected $fillable = [
        'account_managers_id',
        'workers_id',
        'candidates_id',
        'clients_id',
        'email',
        'available_additional_work',
        'client_hourly_rate_usd',
        'requested_pay',
        'worker_monthly_rate',
        'worker_hourly_rate',
        'worker_currency_type',
        'pto',
        'paid_holidays',
        'expected_start_date',
        'ica',
        'special_notes',
        'status',
        'rehire'
    ];
    protected $table = 'hired_candidates';

    public $timestamps = false;

    protected $connection = 'mysql';

    public function accountManager()
    {
        return AccountManagers::where('id', $this->account_managers_id)->first();
    }

    public function client()
    {
        return Clients::where('id', $this->clients_id)->first();
    }

    public function worker()
    {
        return Workers::where('id', $this->workers_id)->first();
    }

    public function candidate()
    {
        return Candidates::where('id', $this->candidates_id)->first();
    }

}
