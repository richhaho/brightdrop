<?php
namespace App;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Laravel\Scout\Searchable;
use Carbon\Carbon;

class DeclinedCandidates extends Model
{
    protected $fillable = [
        'account_managers_id',
        'workers_id',
        'candidates_id',
        'clients_id',
        'email',
        'decline_reason',
        'special_notes',
        'status'
    ];
    protected $table = 'declined_candidates';

    public $timestamps = false;

    protected $connection = 'mysql';

    public function accountManager()
    {
        return AccountManagers::where('id', $this->account_managers_id)->first();
    }

    public function client()
    {
        return Clients::where('id', $this->clients_id)->first();
    }

    public function worker()
    {
        return Workers::where('id', $this->workers_id)->first();
    }

    public function candidate()
    {
        return Candidates::where('id', $this->candidates_id)->first();
    }

}
