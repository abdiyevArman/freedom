<?php

namespace App\Http\Controllers\Admin;

use App\Http\Helpers;
use App\Models\Order;
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
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;

class OrderVacancyController extends Controller
{
    public function __construct()
    {
        $this->middleware('admin');
        View::share('menu', 'order-vacancy');
    }

    public function index(Request $request)
    {
        $row = Order::leftJoin('vacancy','vacancy.vacancy_id','=','order.vacancy_id')
                      ->leftJoin('city','city.city_id','=','vacancy.city_id')
                      ->orderBy('order_id','desc')
                      ->where('type',3)
                      ->select('*',
                          'order.created_at as date');

        if(isset($request->search))
            $row->where(function($query) use ($request){
               $query->where('user_name','like','%' .$request->search .'%');
            });

        if(isset($request->vacancy_name))
            $row->where(function($query) use ($request){
                $query->where('vacancy_name_ru','like','%' .$request->vacancy_name .'%');
            });

        if(isset($request->city_name))
            $row->where(function($query) use ($request){
                $query->where('city_name_ru','like','%' .$request->city_name .'%');
            });

        if(isset($request->active))
            $row->where('order.is_show',$request->active);
        else $row->where('order.is_show','0');

        $row = $row->paginate(10);

        return  view('admin.order-vacancy.order',[
            'row' => $row,
            'title' => 'Отзывы',
            'request' => $request
        ]);
    }

    public function changeIsShow(Request $request){
        $advert = Order::find($request->id);
        $advert->is_show = $request->is_show;
        $advert->save();
    }

    public function destroy($id)
    {
        $user = Order::find($id);
        $user->delete();
    }


    
}
