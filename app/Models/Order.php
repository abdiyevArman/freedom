<?php

namespace App\Models;

use App\Http\Helpers;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use DB;

class Order extends Model
{
    protected $table = 'order';
    protected $primaryKey = 'order_id';

    use SoftDeletes;
    protected $dates = ['deleted_at'];
}
