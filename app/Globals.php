<?php

namespace App;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Laravel\Scout\Searchable;
use Carbon\Carbon;


class Globals extends Model
{
    //use SoftDeletes;
    protected $table = 'global';
    public $timestamps = false;
    protected $connection = 'mysql';
    protected $fillable = [
        'php_usd',
		'mxn_usd',
		'company_name',
		'address',
		'city',
		'state',
		'zip',
		'phone',
		'email',
		'billing_cycle_end_date',
	];

}
