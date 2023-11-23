<?php

namespace App;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Laravel\Scout\Searchable;
use Carbon\Carbon;


class TimeCards extends Model
{
	 
    protected $table = 'time_cards';

    public $timestamps = false;

    protected $connection = 'mysql';
    // public function timesheets()
    // {
    //     $timesheet_ids=explode(",",$this->timesheets_ids);
    //     return TimeSheets::whereIn('id',$timesheet_ids)->orderBy('date')->get();
    // }
    public function timesheets()
    {
        return TimeSheets::where('time_cards_id',$this->id)->orderBy('date');
    }
    public function worker()
    {
        return \App\Workers::where('id',$this->workers_id)->first();
    }
    public function client()
    {
        return \App\Clients::where('id',$this->clients_id)->first();
    }
}
