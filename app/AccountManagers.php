<?php

namespace App;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Laravel\Scout\Searchable;
use Carbon\Carbon;
class AccountManagers extends Model
{
	//use Searchable;
    use SoftDeletes;
    
    protected $table = 'account_managers';

    public $timestamps = false;

    protected $connection = 'mysql';

    public function getFullNameAttribute() {
        return $this->first_name . ' ' . $this->last_name;
    }
    
    public function fullname()
    {
        return $this->first_name. ' '.$this->last_name;
    }
    public function workers()
    {
        //return $this->hasMany('App\Workers')->where('deleted_at',null);
        $clients=$this->clients()->get();
        $worker_ids=array();
        foreach ($clients as $client) {
            $worker_id=$client->workers()->pluck('id')->toArray();
            $worker_ids=array_unique(array_merge($worker_ids,$worker_id));
        }
        $workers=Workers::whereIn('id',$worker_ids)->where('deleted_at',null);
        return $workers;

    }
    public function candidateWorkers()
    {
        return Workers::where('candidate_account_manager_id',$this->id)->whereIn('status', ['new_candidate','pre_candidate','available_hired'])->where('deleted_at',null);
    }
    public function clients()
    {
        return $this->hasMany('App\Clients')->where('deleted_at',null);
    }
    public function contacts()
    {
        //return $this->hasMany('App\Contacts')->where('deleted_at',null);
        $clients=$this->clients()->get();
        $contact_ids=array();
        foreach ($clients as $client) {
            $contact_id=$client->contacts()->pluck('id')->toArray();
            $contact_ids=array_unique(array_merge($contact_ids,$contact_id));
        }
        $contacts=Contacts::whereIn('id',$contact_ids)->where('deleted_at',null);
        return $contacts;
    }
    public function admin()
    {
        return \App\Admins::where('id',$this->admins_id)->first();
    }

    public function cash_advances(){
        return $this->hasMany('App\CashAdvances')->where('deleted_at',null);
    }

    public function user()
    {
        return \App\User::where('id',$this->user_id)->first();
    }
}
