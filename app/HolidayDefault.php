<?php

namespace App;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Laravel\Scout\Searchable;
use Carbon\Carbon;


class HolidayDefault extends Model
{
    protected $table = 'holiday_default';

    public $timestamps = false;

    protected $connection = 'mysql';


}
