<?php

namespace App\Models;

use Illuminate\Auth\Authenticatable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Database\Eloquent\SoftDeletes;
use Hash;
use Illuminate\Support\Str;
use Illuminate\Http\Request;

class Users extends Model implements  AuthenticatableContract
{
    use Authenticatable;

    protected $primaryKey = 'user_id';
    protected $fillable = ['email','password'];

   use SoftDeletes;
    protected $dates = ['deleted_at'];

    public function addNewUser(Request $request)
    {
        $user = Users::where('phone',$request->phone)->first();

        $is_new_user = 0;
        $password = '';
        if($user == null) {
            $user = new Users();
            $is_new_user = 1;
            $password = Str::random(6);
            $user->password = Hash::make($password);
            $user->name = $request->user_name;
            $user->phone = $request->phone;
            $user->role_id = 3;
        }

        $user->iin = $request->iin;
        $user->is_confirm_phone = 1;

        try {
            $user->save();

            $result['user_id'] = $user->user_id;
            $result['is_new_user'] = $is_new_user;
            $result['password'] = $password;

            return $result;
        }
        catch(Exception $ex){
            return false;
        }
    }

}
