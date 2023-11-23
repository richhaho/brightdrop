<?php

namespace App;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Laravel\Scout\Searchable;
use Carbon\Carbon;


class HolidaySchedule extends Model
{
	//use SoftDeletes;
    protected $table = 'holiday_schedule';

    public $timestamps = false;

    protected $connection = 'mysql';


}
