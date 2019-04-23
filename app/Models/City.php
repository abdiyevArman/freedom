<?php

namespace App\Models;

use App\Http\Helpers;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use DB;

class City extends Model
{
    protected $table = 'city';
    protected $primaryKey = 'city_id';

   /* use SoftDeletes;
    protected $dates = ['deleted_at'];*/
}
