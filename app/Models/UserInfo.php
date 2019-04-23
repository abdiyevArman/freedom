<?php

namespace App\Models;

use Illuminate\Auth\Authenticatable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Database\Eloquent\SoftDeletes;

class UserInfo extends Model
{
    protected $table = 'user_info';
    protected $primaryKey = 'user_info_id';

    use SoftDeletes;
    protected $dates = ['deleted_at'];
}
