<?php

namespace App\Models;

use App\Http\Helpers;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use DB;

class Offer extends Model
{
    protected $table = 'offer';
    protected $primaryKey = 'offer_id';

    use SoftDeletes;
    protected $dates = ['deleted_at'];
}
