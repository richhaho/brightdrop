<?php
namespace App;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Laravel\Scout\Searchable;
use Carbon\Carbon;

class Contacts extends Model
{
	//use SoftDeletes;
    protected $table = 'contacts';

    public $timestamps = false;

    protected $connection = 'mysql';

    public function client()
    {
        return \App\Clients::where('id',$this->clients_id)->first();
    }
    public function accountmanager()
    {
        return \App\AccountManagers::where('id',$this->account_managers_id)->first();
    }

    public function user()
    {
        return \App\User::where('id',$this->user_id)->first();
    }
    public function fullname()
    {
        return $this->first_name. ' '.$this->last_name;
    }

}
