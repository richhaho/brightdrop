<?php

namespace App;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Laravel\Scout\Searchable;
use Carbon\Carbon;


class SpecialNotes extends Model
{
    protected $table = 'special_notes';

    public $timestamps = false;

    protected $connection = 'mysql';


}
