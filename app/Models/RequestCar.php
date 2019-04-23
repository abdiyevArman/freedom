<?php

namespace App\Models;

use App\Http\Helpers;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use DB;

class RequestCar extends Model
{
    protected $table = 'request_car';
    protected $primaryKey = 'request_car_id';

    use SoftDeletes;
    protected $dates = ['deleted_at'];
}
