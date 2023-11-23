<?php
namespace App;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Laravel\Scout\Searchable;
use Carbon\Carbon;

class Positions extends Model
{
    protected $fillable = [
        'name',
        'clients_id',
        'status',
        'visible_to_client',
        'editable_to_client',
        'columns'
    ];
    protected $table = 'positions';

    public $timestamps = false;

    protected $connection = 'mysql';

    public function groups()
    {
        return Groups::where('positions_id', $this->id)->where('deleted_at',null)->get();
    }

    public function client()
    {
        return Clients::where('id', $this->clients_id)->where('deleted_at',null)->first();
    }

    public function columns()
    {
        $cols = json_decode($this->columns, true);
        $columns = [];
        foreach ($cols as $col) {
            if ($col['field'] == 'name') {
                $columns[] = $col;
                break;
            }
        }
        foreach ($cols as $col) {
            if ($col['field'] != 'name') {
                $columns[] = $col;
            }
        }
        return $columns;
    }

    public function completedCandidates()
    {
        $groups = $this->groups()->pluck('id');
        return Candidates::whereIn('groups_id', $groups)->where('deleted_at',null)->whereIn('status', ['hired', 'decliend'])->get();
    }
}
