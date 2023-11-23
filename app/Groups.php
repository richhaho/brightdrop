<?php
namespace App;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Laravel\Scout\Searchable;
use Carbon\Carbon;

class Groups extends Model
{
    protected $fillable = [
        'positions_id',
        'name',
        'visible_to_brightdrop',
        'visible_to_client',
        'editable_to_client',
        'can_delete'
    ];
    protected $table = 'groups';

    public $timestamps = false;

    protected $connection = 'mysql';

    public function candidates()
    {
        return Candidates::where('groups_id', $this->id)->where('deleted_at',null)
            ->where(function($query) {
                $query->where('status', null)->orwhere('status', 'new_candidate');
            })->orderBy('sort')->get();
    }

    public function position()
    {
        return Positions::where('id', $this->positions_id)->where('deleted_at',null)->first();
    }

}
