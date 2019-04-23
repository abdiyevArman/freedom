<?php

namespace App\Models;

use App\Http\Helpers;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use DB;

class DocumentType extends Model
{
    protected $table = 'document_type';
    protected $primaryKey = 'document_type_id';

    use SoftDeletes;
    protected $dates = ['deleted_at'];
}
