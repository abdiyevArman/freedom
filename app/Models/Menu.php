<?php

namespace App\Models;

use App\Http\Helpers;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use DB;

class Menu extends Model
{
    protected $table = 'menu';
    protected $primaryKey = 'menu_id';

    use SoftDeletes;
    protected $dates = ['deleted_at'];
}
