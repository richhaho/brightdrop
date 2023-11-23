<?php

namespace App;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Laravel\Scout\Searchable;
use Carbon\Carbon;


class Reimbursement extends Model
{
    use SoftDeletes;
    protected $table = 'reimbursements';

    public $timestamps = false;

    protected $connection = 'mysql';
    public function client()
    {
        return \App\Clients::where('id',$this->clients_id)->first();
    }
    public function worker()
    {
        return \App\Workers::where('id',$this->workers_id)->first();
    }

}
