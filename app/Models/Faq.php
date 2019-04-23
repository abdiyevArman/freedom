<?php

namespace App\Models;

use App\Http\Helpers;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use DB;

class Faq extends Model
{
    protected $table = 'faq';
    protected $primaryKey = 'faq_id';

    use SoftDeletes;
    protected $dates = ['deleted_at'];
}
