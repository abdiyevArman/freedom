<?php

namespace App\Models;

use App\Http\Helpers;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use DB;

class InsuranceRequest extends Model
{
    protected $table = 'insurance_request';
    protected $primaryKey = 'insurance_request_id';

    use SoftDeletes;
    protected $dates = ['deleted_at'];
}
