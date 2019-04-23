<?php

namespace App\Models;

use App\Http\Helpers;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use DB;

class RequestReject extends Model
{
    protected $table = 'request_reject';
    protected $primaryKey = 'request_reject_id';

    use SoftDeletes;
    protected $dates = ['deleted_at'];
}
