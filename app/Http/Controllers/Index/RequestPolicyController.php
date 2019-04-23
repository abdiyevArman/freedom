<?php

namespace App\Http\Controllers\Index;

use App\Http\Helpers;
use App\Models\Contact;
use App\Models\Menu;
use App\Models\News;
use App\Models\Offer;
use App\Models\Order;
use App\Models\Page;
use App\Models\Policy;
use App\Models\RequestPolicy;
use App\Models\Users;
use App\Models\Vacancy;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use Unisender\ApiWrapper\UnisenderApi;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\MessageBag;
use DB;
use Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Response;
use Cookie;



class RequestPolicyController extends Controller
{
    public $lang = 'ru';

    public function __construct()
    {
        $this->lang = Helpers::getSessionLang();
    }

    public function index(Request $request)
    {
        $menu = Menu::where('is_show',1)->where('menu_redirect','/')->first();
        if($menu == null) abort(404);

        return  view('index.index.index',
            [
                'row' => $request,
                'menu' => $menu
            ]);
    }

    public function addRequestPolicy(Request $request){
        $validator = Validator::make($request->all(), [
            'user_name' => 'required',
            'phone' => 'required',
            'transport_name' => 'required',
            'transport_cost' => 'required',
        ]);

        if ($validator->fails()) {
            $messages = $validator->errors();
            $error = $messages->all();
            $result['status'] = false;
            $result['error'] = 'Вам следует указать необходимые данные';
            return $result;
        }

        $policy = new RequestPolicy();
        $policy->user_name = $request->user_name;
        $policy->transport_name = $request->transport_name;
        $policy->transport_cost = $request->transport_cost;
        $policy->phone = $request->phone;
        $policy->email = $request->email;
        $policy->driver_age = $request->driver_age;
        $policy->driver_experience = $request->driver_experience;
        $policy->is_call_gai = $request->is_call_gai?1:0;
        $policy->without_depreciation = $request->without_depreciation?1:0;
        $policy->franchise_size = $request->franchise_size;
        $policy->is_exist_accident = $request->is_exist_accident?1:0;
        $policy->type = 'kasko';
        $policy->is_show = 1;
        $policy->save();

        $result['status'] = true;
        $result['message'] = 'Успешно отправлено';

        return response()->json($result);
    }

    public function addRequestPolicyKaskoExpress(Request $request){
        $validator = Validator::make($request->all(), [
            'user_name' => 'required',
            'phone' => 'required'
        ]);

        if ($validator->fails()) {
            $messages = $validator->errors();
            $error = $messages->all();
            $result['status'] = false;
            $result['error'] = 'Вам следует указать необходимые данные';
            return $result;
        }

        $policy = new RequestPolicy();
        $policy->iin = $request->iin;
        $policy->user_name = $request->user_name;
        $policy->phone = $request->phone;
        $policy->transport_name = $request->transport_name;
        $policy->transport_number = $request->transport_number;
        $policy->insurance_cost = $request->insurance_cost;
        $policy->type = 'kasko-express';
        $policy->is_show = 1;
        $policy->save();

        $result['status'] = true;
        $result['message'] = 'Успешно отправлено';

        return response()->json($result);
    }

}
