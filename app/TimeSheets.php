<?php

namespace App;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Laravel\Scout\Searchable;
use Carbon\Carbon;


class TimeSheets extends Model
{
    protected $table = 'time_sheets';
    public $timestamps = false;
    protected $connection = 'mysql';
}
