<?php

namespace App\Models;

use App\Http\Helpers;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use DB;

class Policy extends Model
{
    protected $table = 'policy';
    protected $primaryKey = 'policy_id';

    /*use SoftDeletes;
    protected $dates = ['deleted_at'];*/
}
