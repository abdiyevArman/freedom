<?php

namespace App\Http\Controllers\Index;

use App\Http\Helpers;
use App\Models\City;
use App\Models\DocumentType;
use App\Models\InsuranceRequest;
use App\Models\RequestCar;
use App\Models\UserDocument;
use App\Models\Menu;
use App\Models\News;
use App\Models\Order;
use App\Models\Users;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\MessageBag;
use DB;
use Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Response;
use Cookie;
use Mockery\CountValidator\Exception;


class InsuranceController extends Controller
{
    public $lang = 'ru';

    public function __construct()
    {
        //$this->middleware('profile');
        $this->lang = Helpers::getSessionLang();
    }

    
    public function showInsurance(Request $request,$url = null)
    {
        $menu =  Menu::leftJoin('menu as parent','parent.menu_id','=','menu.parent_id')
                        ->where('menu.is_show',1)
                        ->where('menu.menu_redirect','/insurance')
                        ->select('menu.*',
                            'parent.menu_name_'.$this->lang.' as parent_name'
                        )
                        ->first();

        if($menu == null) abort(404);

        $city_list = City::orderBy('city.sort_num','asc')
                        ->select('city.*')
                        ->get();

        $user_id = 0;
        if(Auth::check()){
            $user_id = Auth::user()->user_id;

            $row = Users::leftJoin('user_info','user_info.user_id','=','users.user_id')
                ->where('users.user_id',$user_id)
                ->first();
        }
        else {
            $row = new Users();
        }

        $document_types = DocumentType::where('is_show',1)->orderBy('sort_num','asc')->get();

        return  view('index.insurance.insurance',
            [
                'row' => $row,
                'city_list' => $city_list,
                'document_types' => $document_types,
                'menu' => $menu
            ]);
    }

    public function getUploadFile(Request $request)
    {
        $result[0]['file_size'] = $request->file_size;
        $result[0]['file_url'] = $request->file_url;
        $result[0]['file_name'] = $request->file_name;
        $result[0]['document_type'] = $request->document_type;

        return  view('index.insurance.document-list-loop', [
            'document_list' => $result
        ]);
    }

    public function addRequestInsurance(Request $request){
        $validator = Validator::make($request->all(), [
            'last_name' => 'required',
            'first_name' => 'required',
            'policy_number' => 'required',
            'policy_date' => 'required',
            'policy_time' => 'required',
            'city_id' => 'required',
            'causer_name' => 'required',
            'situation_desc' => 'required',
            'phone' => 'required'
        ]);

        if ($validator->fails()) {
            $messages = $validator->errors();
            $error = $messages->all();
            $result['status'] = false;
            $result['error'] = 'Вам следует указать необходимые данные';
            return $result;
        }

        $insurance = new InsuranceRequest();
        $insurance->last_name = $request->last_name;
        $insurance->first_name = $request->first_name;
        $insurance->policy_number = $request->policy_number;
        $insurance->phone = $request->phone;
        $insurance->city_id = $request->city_id;

        if(Auth::check()){
            $insurance->user_id = Auth::user()->user_id;
        }

        $insurance->causer_name = $request->causer_name;
        $insurance->situation_desc = $request->situation_desc;
        //$insurance->is_need_evacuator = $request->is_need_evacuator;

        $timestamp = strtotime($request->policy_date.' '.$request->policy_time);
        $insurance->policy_date = date("Y-m-d H:i", $timestamp);

        $insurance->is_show = 1;

        try {
            $insurance->save();

            if(isset($request->file_names)){
                foreach($request->file_names as $key => $item){
                    $user_document = new UserDocument();
                    $user_document->request_id = $insurance->insurance_request_id;
                    $user_document->file_name = $item;
                    $user_document->file_size = $request['file_sizes'][$key];
                    $user_document->file_url = $request['file_urls'][$key];
                    $user_document->document_type_id = $request['document_types'][$key];
                    $user_document->save();
                }
            }

            if(isset($request->car_policy_list)){
                foreach($request->car_policy_list as $key => $item){
                    if($item != ''){
                        $request_car = new RequestCar();
                        $request_car->request_id = $insurance->insurance_request_id;
                        $request_car->transport_number = $item;
                        $request_car->save();
                    }
                }
            }
        }
        catch(Exception $ex){
            $result['status'] = false;
            $result['message'] = 'Ошибка';
            return response()->json($result);
        }


    }
}
