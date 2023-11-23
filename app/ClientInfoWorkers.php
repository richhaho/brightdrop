<?php
namespace App;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Laravel\Scout\Searchable;
use Carbon\Carbon;
use App\Workers;

 

class ClientInfoWorkers extends Model
{
     
    protected $table = 'clientinfo_workers';

    public $timestamps = false;

    protected $connection = 'mysql';

    public function client()
    {
        return \App\Clients::where('id',$this->clients_id)->first();
    }

}
