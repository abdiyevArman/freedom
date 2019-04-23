<?php

namespace App\Models;

use Illuminate\Auth\Authenticatable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Database\Eloquent\SoftDeletes;

class UserDocument extends Model
{
    protected $table = 'user_document';
    protected $primaryKey = 'user_document_id';

    use SoftDeletes;
    protected $dates = ['deleted_at'];
}
