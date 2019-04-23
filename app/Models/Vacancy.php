<?php

namespace App\Models;

use App\Http\Helpers;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use DB;

class Vacancy extends Model
{
    protected $table = 'vacancy';
    protected $primaryKey = 'vacancy_id';

   /* use SoftDeletes;
    protected $dates = ['deleted_at'];*/
}
