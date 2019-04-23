<?php

namespace App\Http\Controllers\Admin;

use App\Http\Helpers;
use App\Models\InsuranceRequest;
use App\Models\Order;
use App\Models\RequestCar;
use App\Models\Users;
use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\MessageBag;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\File;

use View;
use DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;

class InsuranceController extends Controller
{
    public function __construct()
    {
        $this->middleware('admin');
        View::share('menu', 'insurance');
    }

    public function index(Request $request)
    {
        $row = InsuranceRequest::leftJoin('city','city.city_id','=','insurance_request.city_id')
                      ->orderBy('insurance_request_id','desc')
                      ->select('*',
                          'insurance_request.created_at as date');

        if(isset($request->search))
            $row->where(function($query) use ($request){
               $query->where('first_name','like','%' .$request->search .'%')
                     ->orWhere('last_name','like','%' .$request->search .'%');
            });

        if(isset($request->city_name))
            $row->where(function($query) use ($request){
                $query->where('city_name_ru','like','%' .$request->city_name .'%');
            });

        if(isset($request->policy_number))
            $row->where(function($query) use ($request){
                $query->where('policy_number','like','%' .$request->policy_number .'%');
            });

        if(isset($request->active))
            $row->where('insurance_request.is_show',$request->active);
        else $row->where('insurance_request.is_show','0');

        $row = $row->paginate(10);

        return  view('admin.insurance.request',[
            'row' => $row,
            'title' => 'Отзывы',
            'request' => $request
        ]);
    }

    public function show(Request $request,$id){

        $row = InsuranceRequest::leftJoin('city','city.city_id','=','insurance_request.city_id')
                            ->orderBy('insurance_request_id','desc')
                            ->where('insurance_request_id',$id)
                            ->select('*',
                                'insurance_request.created_at as date',
                                DB::raw('DATE_FORMAT(insurance_request.policy_date,"%d.%m.%Y %H:%i") as policy_date'))
                            ->first();

        if($row == null) abort(404);

        $row->request_car = RequestCar::where('request_id',$row->insurance_request_id)->orderBy('request_car_id','asc')->get();


        return  view('admin.insurance.request-info', [
            'row' => $row
        ]);
    }

    public function changeIsShow(Request $request){
        $advert = InsuranceRequest::find($request->id);
        $advert->is_show = $request->is_show;
        $advert->save();
    }

    public function destroy($id)
    {
        $user = Order::find($id);
        $user->delete();
    }


    
}
