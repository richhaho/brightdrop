<?php

namespace App;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Laravel\Scout\Searchable;
use Carbon\Carbon;

class PayrollManagers extends Model
{
    protected $table = 'payroll_managers';

    public $timestamps = false;

    protected $connection = 'mysql';

    public function user()
    {
        return \App\User::where('id',$this->user_id)->first();
    }


}
