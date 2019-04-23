<?php

namespace App\Http\Controllers\Index;

use App\Http\Helpers;
use App\Models\Arbitrator;
use App\Models\Blog;
use App\Models\Brand;
use App\Models\Category;
use App\Models\Contact;
use App\Models\Menu;
use App\Models\News;
use App\Models\Order;
use App\Models\Page;


use App\Models\Policy;
use App\Models\Product;
use App\Models\Question;
use App\Models\Review;
use App\Models\Service;
use App\Models\Subscription;
use App\Models\UserInfo;
use App\Models\Users;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\MessageBag;
use DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Response;
use Cookie;
use Auth;
use Hash;



class ProfileController extends Controller
{
    public $lang = 'ru';

    public function __construct()
    {
        $this->middleware('profile');
        $this->lang = Helpers::getSessionLang();
    }

    
    public function showProfile(Request $request)
    {
        $row = Users::leftJoin('user_info','user_info.user_id','=','users.user_id')->where('users.user_id',Auth::user()->user_id)->first();
        return  view('index.profile.profile',['row' => $row]);
    }

    public function showPolicy(Request $request)
    {
        $policy = Policy::where('user_id',Auth::user()->user_id)->orderBy('policy_id','desc')->get();

        $success = Session::get('success')?:0;

        return  view('index.profile.policy',
            [
                'policy' => $policy,
                'success' => $success
            ]
        );
    }

    public function editProfile(Request $request){
        $validator = Validator::make($request->all(), [
            'phone' => 'required|unique:users,phone,' .Auth::user()->user_id .',user_id',
            /*'iin' => 'required|unique:users,iin,' .Auth::user()->user_id .',user_id',*/
            'iin' => 'required',
            'first_name' => 'required',
            'last_name' => 'required',
        ]);

        if ($validator->fails()) {
            $messages = $validator->errors();
            $error = $messages->all();

            return  view('index.profile.profile', [
                'error' => $error[0],
                'row' => $request
            ]);

        }

        $users = Auth::user();
        $users->phone = $request->phone;
        $users->iin = $request->iin;
        $users->email = $request->email;


        if($request->phone != $users->phone){
            $sms_code = rand(100000,999999);
            $users->is_confirm_phone = 0;
            $users->sms_code = $sms_code;
            $phone = '7'.Helpers::changePhoneFormat($request->phone);
            $message = "Ваш код подтверждения: ".$sms_code;
            $result = Helpers::sendSMS($phone,$message);
        }

        try {
            $users->save();

            $user_info = UserInfo::where('user_id',$users->user_id)->first();
            if($user_info == null)
                $user_info = new UserInfo();
                $user_info->address = $request->address;
                $user_info->user_id = $users->user_id;
                $user_info->first_name = $request->first_name;
                $user_info->last_name = $request->last_name;
                $user_info->mid_name = $request->mid_name;
                $user_info->save();

            if(isset($request->old_password) && $request->old_password != '' && isset($request->new_password) && $request->new_password != ''){
                $user = Users::where('user_id','=',Auth::user()->user_id)->first();
                $count = Hash::check($request->old_password, $user->password);
                if($count == false){
                    return  view('index.profile.profile',[
                        'error' => 'Неправильный старый пароль',
                        'row' => $request
                    ]);
                }

                $validator = Validator::make($request->all(), [
                    'old_password' => 'required',
                    'new_password' => 'required|different:old_password',
                    'confirm_password' => 'required|same:new_password',
                ]);

                if ($validator->fails()) {
                    $messages = $validator->errors();
                    $error = $messages->all();
                    return  view('index.profile.profile', [
                        'error' => $error[0],
                        'row' => $request
                    ]);
                }

                $user->password = Hash::make($request->new_password);
                $user->save();
            }

        }
        catch(Exception $ex){
            return  view('index.profile.profile', [
                'error' => 'Ошибка',
                'row' => $request
            ]);
        }

        $user_row = Users::leftJoin('user_info','user_info.user_id','=','users.user_id')->where('users.user_id',$users->user_id)->first();

        return  view('index.profile.profile', [
            'message' => 'Успешно сохранено',
            'row' => $user_row
        ]);
    }



}
