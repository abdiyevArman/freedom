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
use Excel;

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

        if($request->date_from != ''){
            $row->where('request_policy.created_at','>=',$request->date_from);
        }

        if($request->date_to != ''){
            $row->where('request_policy.created_at','<=',$request->date_to);
        }

        if($request->transport_name != '')
            $row->where(function($query) use ($request){
                $query->where('transport_name','like','%' .$request->transport_name .'%');
            });

        if($request->email != '')
            $row->where(function($query) use ($request){
                $query->where('email','like','%' .$request->email .'%');
            });

        if(isset($request->active)){
            $row->where('request_policy.is_show',$request->active);
        }
        else {
            $row->where('request_policy.is_show',0);
        }

        $row = $row->paginate(20);

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

    public function exportExcel(Request $request,$type = 'kasko')
    {
        $row = RequestPolicy::orderBy('request_policy_id','desc')
                            ->where('type',$type)
                            ->take(1000)
                            ->select('*');

        if($request->date_from != ''){
            $row->where('request_policy.created_at','>=',$request->date_from);
        }

        if($request->date_to != ''){
            $row->where('request_policy.created_at','<=',$request->date_to);
        }

        $row = $row->get();

        $param = [];

        if($type == 'kasko'){

            $param[] = ['№', 'ФИО','Email','Телефон','Модель авто','Стоимость авто','Возраст','Стаж вождения',
                        'Размер франшизы','Без учета амортизации','Вызов гаи','ДТП за посл. 2 года','Дата'];

            foreach ($row as $key => $client) {
                $param[$key + 1][0] = $key + 1;
                $param[$key + 1][1] = $client->user_name;
                $param[$key + 1][2] = $client->email;
                $param[$key + 1][3] = $client->phone;
                $param[$key + 1][4] = $client->transport_name;
                $param[$key + 1][5] = $client->transport_cost.' тг';
                $param[$key + 1][6] = $client->driver_age;
                $param[$key + 1][7] = $client->driver_experience;
                $param[$key + 1][8] = $client->franchise_size;
                $param[$key + 1][9] = ($client->without_depreciation == 1)?"Без":"С учетом";
                $param[$key + 1][10] = ($client->is_call_gai == 1)?"Да":"Нет";
                $param[$key + 1][11] = ($client->is_exist_accident == 1)?"Да":"Нет";
                $param[$key + 1][12] = Helpers::getDateFormat3($client->created_at);
            }

        }
        elseif($type == 'kasko-express'){

            $param[] = ['№', 'ФИО','ИИН','Телефон','Модель авто','Номер','Сумма страхования','Дата'];

            foreach ($row as $key => $client) {
                $param[$key + 1][0] = $key + 1;
                $param[$key + 1][1] = $client->user_name;
                $param[$key + 1][2] = $client->iin;
                $param[$key + 1][3] = $client->phone;
                $param[$key + 1][4] = $client->transport_name;
                $param[$key + 1][5] = $client->transport_number;
                $param[$key + 1][6] = $client->insurance_cost.' тг';
                $param[$key + 1][7] = Helpers::getDateFormat3($client->created_at);
            }

        }


        $excel_name = 'Список заявок - '.$type;

        // Generate and return the spreadsheet
        Excel::create($excel_name, function($excel) use ($param,$type) {

            // Set the spreadsheet title, creator, and description
            $excel->setTitle('Список заявок - '.$type);
            $excel->setCreator('Freedom')->setCompany('Freedom');
            $excel->setDescription('Список заявок');

            // Build the spreadsheet, passing in the payments array
            $excel->sheet('sheet1', function($sheet) use ($param) {
                $sheet->fromArray($param, null, 'A1', false, false);
            });

        })->download('xls');
    }
    
}
