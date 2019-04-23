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

class OrderController extends Controller
{
    public function __construct()
    {
        $this->middleware('admin');
        View::share('menu', 'order');
    }

    public function index(Request $request)
    {
        $row = Order::orderBy('order_id','desc')
                      ->where('type',1)
                      ->select('*',
                          'order.created_at as date');

        if(isset($request->search))
            $row->where(function($query) use ($request){
               $query->where('user_name','like','%' .$request->search .'%')
              ->orWhere('phone','like','%' .$request->search .'%');
            });

        if(isset($request->active))
            $row->where('order.is_show',$request->active);
        else $row->where('order.is_show','0');

        $row = $row->paginate(10);

        return  view('admin.order.order',[
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
