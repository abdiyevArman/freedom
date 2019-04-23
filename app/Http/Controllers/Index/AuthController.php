<?php

namespace App\Http\Controllers\Index;

use App\Http\Helpers;

use App\Models\Users;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\MessageBag;
use DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Response;
use Cookie;
use Hash;
use Auth;
use Mockery\CountValidator\Exception;


class AuthController extends Controller
{
    public $lang = 'ru';

    public function __construct()
    {
        $this->middleware('AuthProfile')->except('confirm','showConfirm','logout','setNewNumber','sendSMS','showNewPassword','setNewPassword');
        $this->middleware('CheckSmsActivate')->only('confirm','showConfirm');
    }

    public function showLogin(Request $request)
    {
        return  view('index.auth.login');
    }

    public function showResetPassword(Request $request)
    {
        return  view('index.auth.reset');
    }

    public function showRegister(Request $request)
    {
        return  view('index.auth.register');
    }

    public function showNewPassword(Request $request)
    {
        $check = Session::get('is_after_reset')?:'';

        if($check == '' || $check == null) return redirect('profile');

        return  view('index.auth.password');
    }

    public function showConfirm(Request $request)
    {
        $previous_url = strstr($request->session()->previousUrl(),'?',true);
        $is_after_register = 0;
        if($request->session()->previousUrl() == URL('/').'/auth/register' || $previous_url == URL('/').'/auth/register'){
            $is_after_register = 1;
        }

        return  view('index.auth.confirm', [
                'is_after_register' => $is_after_register
            ]);
    }

    public function showConfirmResetPassword(Request $request)
    {
        $phone = Session::get('phone')?:'';

        if($phone == '' || $phone == null) return redirect('auth/reset');

        return  view('index.auth.confirm-reset',['phone' => $phone]);
    }

    public function setNewNumber(Request $request)
    {
        Auth::logout();
        return redirect('/auth/register');
    }

    public function sendSmsConfirm(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'phone' => 'required'
        ]);

        if ($validator->fails()) {
            $messages = $validator->errors();
            $error = $messages->all();

            return  view('index.auth.reset', [
                'error' => $error[0],
                'request' => $request
            ]);
        }

        $users = Users::where('phone',$request->phone)->first();
        if($users == null){
            return  view('index.auth.reset', [
                'error' => "Пользователь с таким номером не существует",
                'request' => $request
            ]);
        }

        try {
            $sms_code = rand(100000,999999);
            $users->sms_code = $sms_code;
            $users->save();

            $phone = '7'.Helpers::changePhoneFormat($request->phone);
            $message = "Ваш код подтверждения: ".$sms_code;
            $result = Helpers::sendSMS($phone,$message);
        }
        catch(Exception $ex){
            return  view('index.auth.reset', [
                'error' => 'Ошибка'
            ]);
        }

        return redirect('auth/confirm-reset')->with('phone',$request->phone);
    }

    public function confirmResetPassword(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'phone' => 'required',
            'code' => 'required'
        ]);

        if ($validator->fails()) {
            $messages = $validator->errors();
            $error = $messages->all();

            return  view('index.auth.confirm-reset', [
                'error' => $error[0],
                'phone' => $request->phone,
                'code' => $request->code
            ]);
        }

        $user = Users::where('phone',$request->phone)->where('sms_code',$request->code)->first();
        if($user == null){
            return  view('index.auth.confirm-reset', [
                'error' => "Неправильный код",
                'phone' => $request->phone,
                'code' => $request->code
            ]);
        }

        $user->is_confirm_phone = 1;
        $user->save();

        if(Auth::loginUsingId($user->user_id)){
            return redirect('auth/password')->with('is_after_reset',1);
        }
        else {
            return  view('index.auth.confirm-reset', [
                'error' => "Ошибка",
                'phone' => $request->phone,
                'code' => $request->code
            ]);
        }
    }

    public function register(Request $request){
        $validator = Validator::make($request->all(), [
            'phone' => 'required|unique:users,phone,NULL,user_id,deleted_at,NULL',
            'password' => 'required|min:5',
            'confirm_password' => 'required|min:5|same:password',
        ]);

        if ($validator->fails()) {
            $messages = $validator->errors();
            $error = $messages->all();

            return  view('index.auth.register', [
                'error' => $error[0]
            ]);

        }

        $users = new Users();
        $users->phone = $request->phone;
        $users->role_id = 3;
        $users->password = Hash::make($request->password);

        $sms_code = rand(100000,999999);
        //$sms_code = 777888;
        $users->sms_code = $sms_code;

        try {
            $users->save();

            $phone = '7'.Helpers::changePhoneFormat($request->phone);
            $message = "Ваш код подтверждения: ".$sms_code;
            $result = Helpers::sendSMS($phone,$message);

            $userdata = array(
                'phone' => $request->phone,
                'password' => $request->password
            );

            if (!Auth::attempt($userdata))
            {
                $error = 'Неправильный логин или пароль';
                return  view('index.auth.register', [
                    'login' => $request->login,
                    'error' => $error
                ]);
            }

        }
        catch(Exception $ex){
            return  view('index.auth.register', [
                'error' => 'Ошибка'
            ]);
        }

        return redirect('auth/confirm');
    }

    public function login(Request $request){
        $validator = Validator::make($request->all(), [
            'phone' => 'required',
            'password' => 'required',
        ]);

        if ($validator->fails()) {
            $messages = $validator->errors();
            $error = $messages->all();

            return  view('index.auth.login', [
                'error' => "Укажите необходимые данные"
            ]);
        }

        try {
            $userdata = array(
                'phone' => $request->phone,
                'password' => $request->password
            );

            if (!Auth::attempt($userdata))
            {
                $error = 'Неправильный логин или пароль';
                return  view('index.auth.login', [
                    'login' => $request->login,
                    'error' => $error
                ]);
            }

        }
        catch(Exception $ex){
            return  view('index.auth.login', [
                'error' => 'Ошибка'
            ]);
        }

        return redirect('profile');
    }

    public function confirm(Request $request){
        $validator = Validator::make($request->all(), [
            'code' => 'required',
        ]);

        if ($validator->fails()) {
            $messages = $validator->errors();
            $error = $messages->all();

            return  view('index.auth.confirm', [
                'error' => 'Укажите код'
            ]);

        }

        $users = Auth::user();
        if($users->sms_code != $request->code){
            return  view('index.auth.confirm', [
                'error' => 'Неправильный код'
            ]);
        }

        try {
            $users->is_confirm_phone = 1;
            $users->save();
        }
        catch(Exception $ex){
            return  view('index.auth.confirm', [
                'error' => 'Ошибка'
            ]);
        }

        return redirect('profile');
    }

    public function sendSMS(Request $request){
        $users = Auth::user();
        if($users == null) $users = Users::where('phone',$request->phone)->first();

        if($users == null){
            $result['error'] = "Ошибка";
            $result['status'] = true;
            return $result;
        }

        $sms_code = rand(100000,999999);
        $users->sms_code = $sms_code;

        try {
            $users->save();

            $phone = '7'.Helpers::changePhoneFormat($users->phone);
            $message = "Ваш код подтверждения: ".$sms_code;
            $result_sms = Helpers::sendSMS($phone,$message);
        }
        catch(Exception $ex){
            $result['status'] = false;
            $result['error'] = 'Ошибка';
            return $result;
        }

        $result['status'] = true;
        return $result;
    }

    public function setNewPassword(Request $request){
        $validator = Validator::make($request->all(), [
            'password' => 'required|min:5',
            'confirm_password' => 'required|min:5|same:password',
        ]);

        if ($validator->fails()) {
            $messages = $validator->errors();
            $error = $messages->all();

            return  view('index.auth.password', [
                'error' => $error[0]
            ]);

        }

        $users = Auth::user();
        $users->password = Hash::make($request->password);

        try {
            $users->save();
        }
        catch(Exception $ex){
            return  view('index.auth.password', [
                'error' => 'Ошибка'
            ]);
        }

        return redirect('profile');
    }

    public function logout(){
        Auth::logout();
        return redirect('/');
    }
}
