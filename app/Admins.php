<?php

namespace App;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Laravel\Scout\Searchable;
use Carbon\Carbon;

class Admins extends Model
{
    use SoftDeletes;
    protected $table = 'admins';

    public $timestamps = false;

    protected $connection = 'mysql';

    public function fullname()
    {
        return $this->first_name. ' '.$this->last_name;
    }
    public function workers()
    {
        return $this->hasMany('App\Workers');
    }
    public function clients()
    {
        return $this->hasMany('App\Clients');
    }
    public function accountmanagers()
    {
        return $this->hasMany('App\AccountManagers');
    }
    public function user()
    {
        return \App\User::where('id',$this->user_id)->first();
    }
    
}
