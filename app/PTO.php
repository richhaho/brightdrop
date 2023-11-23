<?php

namespace App;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Laravel\Scout\Searchable;
use Carbon\Carbon;


class PTO extends Model
{
    protected $table = 'pto';

    public $timestamps = false;

    protected $connection = 'mysql';

    public function worker()
    {
        return \App\Workers::where('id',$this->workers_id)->first();
    }

}
