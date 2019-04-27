<?php

namespace App\Models;

use App\Http\Helpers;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use DB;

class PolicyProduct extends Model
{
    protected $table = 'policy_product';
    protected $primaryKey = 'policy_product_id';

    use SoftDeletes;
    protected $dates = ['deleted_at'];
}
