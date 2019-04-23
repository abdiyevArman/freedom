<?php

namespace App\Models;

use App\Http\Helpers;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use DB;

class RequestPolicy extends Model
{
    protected $table = 'request_policy';
    protected $primaryKey = 'request_policy_id';

    use SoftDeletes;
    protected $dates = ['deleted_at'];
}
