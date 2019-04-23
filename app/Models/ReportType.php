<?php

namespace App\Models;

use App\Http\Helpers;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use DB;

class ReportType extends Model
{
    protected $table = 'report_type';
    protected $primaryKey = 'report_type_id';

    /*use SoftDeletes;
    protected $dates = ['deleted_at'];*/
}
