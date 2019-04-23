<?php

namespace App\Models;

use App\Http\Helpers;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use DB;

class Team extends Model
{
    protected $table = 'team';
    protected $primaryKey = 'team_id';

    use SoftDeletes;
    protected $dates = ['deleted_at'];
}
