<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Laravel\Scout\Searchable;
use Carbon\Carbon;
use App\ClientInfoWorkers; 

class Workers extends Model
{
	//use Searchable;
    //use SoftDeletes;
    protected $fillable = [
        'user_id',
        'clients_id',
        'status',
        'first_name',
        'last_name',
        'legal_name',
        'email_main',
        'email_veem',
        'skype',
        'phone',
        'country',
        'philippines_region',
        'address',
        'birthday',
        'gender',
        'currency_type',
        
        'candidate_account_manager_id',
        'fulltime_compensation_amount',
        'fulltime_compensation_currency',
        'available_hours',
        'outside_brightdrop',
        'hours_outside_perweek',
        'current_nonbrightdrop_hours',
        'available_start_date',
        'video_link',
        'video_sub_link',
        'writing_sample',
        'worker_source',
        'english_skills',
        'skills',
        'skill_other',
        'software_knowledge',
        'software_other',
        'internet_connection',
        'computer',
         
        'work_schedule',
        'backup_plan',
        'emergency_contacts',
        'payments',
        'pto_summary',
        'disqualifier_explain',
        
        'video_file',
        'resume_file',
        'internal_recruitment_manager',
        'internal_other_employee',
        'Onlinelinejobs_profilelink',
        'worker_referral',
        'worksource_other',
        'english_verbal',
        'english_verbal_note',
        'english_written',
        'english_written_note',
        'emergency_contact1_fullname',
        'emergency_contact1_relationship',
        'emergency_contact1_email',
        'emergency_contact1_phone',
        'emergency_contact1_address',
        'emergency_contact2_fullname',
        'emergency_contact2_relationship',
        'emergency_contact2_email',
        'emergency_contact2_phone',
        'emergency_contact2_address',

        'internet_connection_primary',
        'internet_connection_primary_other',
        'internet_connection_primary_type',
        'internet_connection_primary_speed',
        'internet_connection_primary_screenshot',
        'internet_connection_primary_data_cap',
        'backup_connection',
        'backup_connection_isp',
        'backup_connection_other',
        'backup_connection_type',
        'backup_connection_speed',
        'backup_connection_screenshot',
        'backup_connection_data_cap',
        'internet_connection_note',
        'primary_computer_type',
        'primary_computer_brand',
        'primary_computer_model',
        'primary_computer_age',
        'primary_computer_system',
        'backup_computer',
        'backup_computer_type',
        'backup_computer_brand',
        'backup_computer_model',
        'backup_computer_age',
        'backup_computer_system',
        'technical_computer_note',
        'typing_test_wpm',
        'typing_test_number_of_errors',
        'speaks_spanish',
        'us_business_hours',
        'home_based_experience',
        'home_based_additional_info',
        'reliable_quiet_workspace',
        'long_term_work_issues',
        'ica',
        'typing_test_file',
        'temp_video_link'
    ];
    protected $table = 'workers';

    public $timestamps = false;

    protected $connection = 'mysql';

    public function getFullNameAttribute() {
        return $this->first_name . ' ' . $this->last_name;
    }

    public function getFullNameDotAttribute() {
        return $this->fullname() . '&nbsp;&nbsp;  &#128308;';
    }

    public function fullname()
    {
        return $this->first_name. ' '.$this->last_name;
    }

    public function clients()
    {
        $client_ids=explode(",",$this->clients_ids);
        return Clients::whereIn('id',$client_ids)->get();
    }
    public function activeClients()
    {
        $cids=explode(",",$this->clients_ids);
        $client_ids = ClientInfoWorkers::where('workers_id', $this->id)->whereIn('clients_id', $cids)->where('status', 'active')->get()->pluck('clients_id')->toArray();
        return Clients::whereIn('id',$client_ids)->get();
    }
    public function accountmanager()
    {
        return \App\AccountManagers::where('id',$this->account_managers_id)->first();
    }
    public function user()
    {
        return \App\User::where('id',$this->user_id)->first();
    }

    public function timesheets()
    {
        return $this->hasMany('App\TimeSheets');
    }
    public function timecards()
    {
        return $this->hasMany('App\TimeCards');
    }

    public function ptos()
    {
        return $this->hasMany('App\PTO');
    }
    public function reimbursements()
    {
        return $this->hasMany('App\Reimbursement');
    }
    public function specialCandiateNotes()
    {
        return $this->hasMany('App\SpecialNotes');
    }
    public function payments()
    {
        return $this->hasMany('App\Payments');
    }
    
    public function candidates()
    {
        return Candidates::where('name', $this->id)->get();
    }

    public function fullnameWithDot($client_id, $status) {
        $timecards=TimeCards::where('status', $status)->where('workers_id', $this->id);
        if ($client_id && $client_id != 'all') {
            $timecards=$timecards->where('clients_id',$client_id);
        } else if ($client_id == 'all') {
            $all = Clients::where('deleted_at',null)->where('status', 'active')->pluck('id')->toArray();
            $timecards=$timecards->whereIn('clients_id',$all);
        }
        return $this->fullname() . ($timecards->count() > 0 ? '&nbsp;&nbsp;  &#128308;' : '');
    }

    public function ptoHoursUsedForYear($client_id, $year)
    {
        $ptoTimesheets = $this->timesheets()->where('clients_id',$client_id)->where('status','Approved')->where('date','like', $year);
        $ptoHoursUpdated=$ptoTimesheets->sum('pto_time_hours_updated');
        $ptoHours=$ptoTimesheets->where('pto_time_hours_updated', null)->sum('pto_time_hours');
        return $ptoHours + $ptoHoursUpdated;
    }
}
