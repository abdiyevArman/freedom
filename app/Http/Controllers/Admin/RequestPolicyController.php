<?php

namespace App\Http\Controllers\Admin;

use App\Http\Helpers;
use App\Models\RequestPolicy;
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

class RequestPolicyController extends Controller
{
    public function __construct()
    {
        $this->middleware('admin');
    }

    public function index(Request $request, $type = 'kasko')
    {
        if($type != 'kasko' && $type != 'kasko-express') abort(404);

        $row = RequestPolicy::orderBy('request_policy_id','desc')
                      ->where('type',$type)
                      ->select('*',
                          'request_policy.created_at as date');

        if(isset($request->search))
            $row->where(function($query) use ($request){
               $query->where('user_name','like','%' .$request->search .'%');
            });

        if(isset($request->transport_name))
            $row->where(function($query) use ($request){
                $query->where('transport_name','like','%' .$request->transport_name .'%');
            });

        if(isset($request->email))
            $row->where(function($query) use ($request){
                $query->where('email','like','%' .$request->email .'%');
            });

        if(isset($request->active))
             $row->where('request_policy.is_show',$request->active);
        else $row->where('request_policy.is_show','0');

        $row = $row->paginate(10);

        View::share('menu', 'request-policy-'.$type);

        return  view('admin.request-policy.'.$type,[
            'row' => $row,
            'title' => 'Отзывы',
            'request' => $request
        ]);
    }

    public function changeIsShow(Request $request){
        $advert = RequestPolicy::find($request->id);
        $advert->is_show = $request->is_show;
        $advert->save();
    }

    public function destroy($id)
    {
        $user = RequestPolicy::find($id);
        $user->delete();
    }


    
}
