<?php
namespace App;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Laravel\Scout\Searchable;
use Carbon\Carbon;
use App\Workers;
use App\AccountManagers;
use App\ClientInfoWorkers;
 

class Clients extends Model
{
	//use Searchable;
    //use SoftDeletes;
    protected $fillable = [
        'workers_id',
        'status',
        'client_name',
        'website',
        'phone',
        'country',
        'address1',
        'address2',
        'address_foreign',
        'city',
        'state',
        'zip',
        'business_development',
        'account_manager',
        'industry',
        'job_function',
        'lead_generated_by',
        'direct_contact_business_accountmanager',
        'billing_cycle_next_end_date',
        'billing_cycle_type',
        'billing_cycle_next_end_date_adder',
        'overtime_pay_provided',
        'overtime_percent',
        'invoice_method',
        'ACH_discount_participation',
        'payment_method',
        'review_time',
        'pto_infomation',
        'who_pays_pto',
        'default_pto_days',
        'holiday_shedule_offered',
        'who_pays_holiday',
        'holidays',
        'contacts',
        'workers',
        'country_other',
        'account_managers_id',
        'industry_other',
        'job_function_other',
        'direct_contact_internal_payroll_admin',
        'marketing_program_other',
        'client_referral',
        'lead_generated_other',
        'lunchtime_billable',
        'lunchtime_billable_max_minutes',
        'breaktime_billable',
        'breaktime_billable_max_minutes',
        'internal_processor',
        'include_PTO_in_overtime_invoice',
        'include_PH_in_overtime_invoice'
    ];
    protected $table = 'clients';

    public $timestamps = false;

    protected $connection = 'mysql';

    public function accountmanager()
    {
        return AccountManagers::where('id', $this->account_managers_id)->where('deleted_at',null)->first();
    }
    public function workers()
    {
        $worker_ids=explode(",",$this->workers_ids);
        return Workers::whereIn('id',$worker_ids)->where('deleted_at',null)->orderBy('last_name')->orderBy('first_name')->get();
        //return $this->hasMany('App\Workers')->withTrashed()->first();
    }
    public function activeWorkers()
    {
        $wids=explode(",",$this->workers_ids);
        $worker_ids = ClientInfoWorkers::where('clients_id', $this->id)->whereIn('workers_id', $wids)->where('status', 'active')->get()->pluck('workers_id')->toArray();
        return Workers::whereIn('id',$worker_ids)->where('deleted_at',null)->orderBy('first_name')->get();
    }
    public function inactiveWorkers()
    {
        $wids=explode(",",$this->workers_ids);
        $worker_ids = ClientInfoWorkers::where('clients_id', $this->id)->whereIn('workers_id', $wids)->where('status', 'inactive')->get()->pluck('workers_id')->toArray();
        return Workers::whereIn('id',$worker_ids)->where('deleted_at',null)->orderBy('first_name')->get();
    }
    public function assignedWorkers()
    {
        $wids=explode(",",$this->workers_ids);
        $worker_ids = ClientInfoWorkers::where('clients_id', $this->id)->whereIn('workers_id', $wids)->get()->pluck('workers_id')->toArray();
        return Workers::whereIn('id',$worker_ids)->where('deleted_at',null)->orderBy('first_name')->get();
    }
    public function contacts()
    {
        //return Workers::whereIn('id',$worker_ids)->get();
        return $this->hasMany('App\Contacts')->where('deleted_at',null)->orderBy('last_name')->orderBy('first_name');
    }
    public function assigned_worker_info()
    {
        //return Workers::whereIn('id',$worker_ids)->get();
        return $this->hasMany('App\ClientInfoWorkers')->where('deleted_at',null);
    }
    public function timesheets()
    {
        return $this->hasMany('App\TimeSheets')->where('deleted_at',null);
    }
    public function timecards()
    {
        return $this->hasMany('App\TimeCards')->where('deleted_at',null);
    }
    public function holidays()
    {
        return $this->hasMany('App\HolidaySchedule');
    }
    public function invoices()
    {
        return $this->hasMany('App\Invoices');
    }
    public function positions()
    {
        return $this->hasMany('App\Positions');
    }
    public function visiblePositions()
    {
        return $this->hasMany('App\Positions')->where('visible_to_client', 'yes');
    }

    // public function activeWorkersObject()
    // {
    //     $wids=explode(",",$this->workers_ids);
    //     $worker_ids = ClientInfoWorkers::where('clients_id', $this->id)->whereIn('workers_id', $wids)->where('status', 'active')->get()->pluck('workers_id')->toArray();
    //     return Workers::whereIn('id',$worker_ids)->where('deleted_at',null);
    // }
    // public function inactiveWorkersObject()
    // {
    //     $wids=explode(",",$this->workers_ids);
    //     $worker_ids = ClientInfoWorkers::where('clients_id', $this->id)->whereIn('workers_id', $wids)->where('status', 'inactive')->get()->pluck('workers_id')->toArray();
    //     return Workers::whereIn('id',$worker_ids)->where('deleted_at',null);
    // }
    public function assignedWorkersObject($search = [])
    {
        $wids=explode(",",$this->workers_ids);
        $worker_ids = ClientInfoWorkers::where('clients_id', $this->id)->whereIn('workers_id', $wids);
        foreach ($search as $key => $value) {
            if ($value) {
                $worker_ids->where($key, $value);
            }
        }
        $worker_ids = $worker_ids->get()->pluck('workers_id')->toArray();
        return Workers::whereIn('id',$worker_ids)->where('deleted_at',null);
    }

    public function assignedWorkerObject($worker)
    {
        return ClientInfoWorkers::where('clients_id', $this->id)->where('workers_id', $worker->id)->first();
    }

    public function pendingWorkerApprovalTimecardsCount()
    {
        return $this->getTimecardsCount('pending_worker');
    }

    public function needsApprovalTimecardsCount()
    {
        return $this->getTimecardsCount('needs_approval');
    }

    public function getTimecardsCount($status)
    {
        $wids=explode(",",$this->workers_ids);
        return $this->hasMany('App\TimeCards')->whereIn('workers_id', $wids)->where('deleted_at',null)->where('status',$status)->get()->count();
    }
}
