<?php

namespace App\Http\Controllers\Admin;

use App\Http\Helpers;
use App\Models\Order;
use App\Models\Policy;
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
use Excel;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;

class PolicyController extends Controller
{
    public function __construct()
    {
        $this->middleware('admin');
        View::share('menu', 'policy');
    }

    public function index(Request $request)
    {
        $row = Policy::orderBy('policy_id','desc')
                      ->select('*',
                          'policy.created_at as date');

        if($request->search != '')
            $row->where(function($query) use ($request){
               $query->where('user_name','like','%' .$request->search .'%')
              ->orWhere('phone','like','%' .$request->search .'%');
            });

        /*if(isset($request->active))
            $row->where('order.is_show',$request->active);
        else $row->where('order.is_show','0');*/

        if($request->date_from != ''){
            $row->where('policy.created_at','>=',$request->date_from);
        }

        if($request->date_to != ''){
            $row->where('policy.created_at','<=',$request->date_to);
        }

        $row = $row->paginate(10);

        return  view('admin.policy.policy',[
            'row' => $row,
            'title' => 'Полисы',
            'request' => $request
        ]);
    }

    public function changeIsShow(Request $request){
        $advert = Policy::find($request->id);
        $advert->is_show = $request->is_show;
        $advert->save();
    }

    public function destroy($id)
    {
        $user = Order::find($id);
        $user->delete();
    }

    public function exportExcel(Request $request)
    {
        $row = Policy::orderBy('policy_id','desc')
            ->where('is_pay',1)
            ->take(1000)
            ->select('*');

        if($request->date_from != ''){
            $row->where('policy.created_at','>=',$request->date_from);
        }

        if($request->date_to != ''){
            $row->where('policy.created_at','<=',$request->date_to);
        }

        $row = $row->get();

        $param = [];

        $param[] = ['№', 'ФИО','ИИН','Email','Телефон','Номер полиса','Модель авто','Дата'];

        foreach ($row as $key => $client) {
            $param[$key + 1][0] = $key + 1;
            $param[$key + 1][1] = $client->user_name;
            $param[$key + 1][2] = $client->iin;
            $param[$key + 1][3] = $client->email;
            $param[$key + 1][4] = $client->phone;
            $param[$key + 1][5] = $client->policy_number;
            $param[$key + 1][6] = $client->transport_name;
            $param[$key + 1][7] = Helpers::getDateFormat3($client->created_at);
        }


        $excel_name = 'Список заявок - ОГПО';

        // Generate and return the spreadsheet
        Excel::create($excel_name, function($excel) use ($param) {

            // Set the spreadsheet title, creator, and description
            $excel->setTitle('Список заявок - ОГПО');
            $excel->setCreator('Freedom')->setCompany('Freedom');
            $excel->setDescription('Список заявок');

            // Build the spreadsheet, passing in the payments array
            $excel->sheet('sheet1', function($sheet) use ($param) {
                $sheet->fromArray($param, null, 'A1', false, false);
            });

        })->download('xls');
    }
    
}
