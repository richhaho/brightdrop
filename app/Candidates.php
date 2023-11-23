<?php
namespace App;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Laravel\Scout\Searchable;
use Carbon\Carbon;

class Candidates extends Model
{
    protected $fillable = [
        'groups_id',
        'name',
        'worker_profile',
        'email_address',
        'video_profile',
        'country',
        'requested_pay',
        'worker_monthly_rate',
        'worker_hourly_rate',
        'worker_currency_type',
        'client_hourly_rate',
        'available_start_date',
        'worker_source',
        'internal_notes',
        'shared_notes',
        'shared_notes_client',
        'other_columns',
        'status',
        'hired_status',
        'declined_status',
        'sort'
    ];
    protected $table = 'candidates';

    public $timestamps = false;

    protected $connection = 'mysql';

    public function group()
    {
        return Groups::where('id', $this->groups_id)->where('deleted_at',null)->first();
    }

    public function hireds()
    {
        return HiredCandidates::where('candidates_id', $this->id)->where('deleted_at',null)->get();
    }

    public function worker()
    {
        return Workers::where('id', $this->name)->first();
    }

}
