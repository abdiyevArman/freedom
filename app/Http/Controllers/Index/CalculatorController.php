<?php

namespace App\Http\Controllers\Index;

use App\Http\Helpers;
use App\Models\Contact;
use App\Models\Menu;
use App\Models\News;
use App\Models\Order;
use App\Models\Page;


use App\Models\Policy;
use App\Models\Users;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\MessageBag;
use DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Response;
use Cookie;
use Auth;
use Hash;
use Illuminate\Support\Str;
use Mockery\CountValidator\Exception;
use Unisender\ApiWrapper\UnisenderApi;

class CalculatorController extends Controller
{
    public $lang = 'ru';

    public function __construct()
    {
        $this->lang = Helpers::getSessionLang();
    }

    public function showOGPO(Request $request)
    {
        $menu = Menu::where('menu.is_show',1)
                    ->where('menu.menu_redirect','/ogpo')
                    ->select('menu.*')
                    ->first();

        if($menu == null) abort(404);

        return  view('index.calculate.ogpo.ogpo',
            [
                'row' => $request,
                'menu' => $menu
            ]);
    }

    public function showKASKO(Request $request)
    {
        $menu = Menu::where('menu.is_show',1)
            ->where('menu.menu_redirect','/kasko')
            ->select('menu.*')
            ->first();

        if($menu == null) abort(404);

        return  view('index.calculate.kasko.kasko',
            [
                'row' => $request,
                'menu' => $menu
            ]);
    }

    public function showKaskoExpress(Request $request)
    {
        $menu = Menu::where('menu.is_show',1)
            ->where('menu.menu_redirect','/kasko-express')
            ->select('menu.*')
            ->first();


        if($menu == null) abort(404);

        return  view('index.calculate.kasko-express.kasko-express',
            [
                'row' => $request,
                'menu' => $menu
            ]);
    }

    public function checkValidatePolicy(Request $request)
    {
        if($request->iin != ''){
            if(strlen((string) $request->iin) != 12){
                $result['status'] = false;
                $result['error'] = 'Укажите корректный ИИН';
                return response()->json($result);
            }

            $esbd = new ESBDController();
            $result_info = $esbd->checkValidatePolicy($request->iin);
            if(isset($result_info['data'][0])){
                if(isset($result_info['data'][0]['active']) && $result_info['data'][0]['active'] == true){
                    $result['status'] = true;
                    $result['message'] = 'Ваш полис действует до '.Helpers::getDateFormat3($result_info['data'][0]['date_stop']);
                    return response()->json($result);
                }
                else {
                    $count = count($result_info['data']);
                    if($count > 1){
                        foreach($result_info['data'] as $item){
                            if(isset($item['active']) && $item['active'] == true){
                                $result['status'] = true;
                                $result['message'] = 'Ваш полис действует до '.Helpers::getDateFormat3($item['date_stop']);
                                return response()->json($result);
                            }
                        }
                    }
                }
            }

            $result['status'] = true;
            $result['message'] = 'У вас нет активных полисов';

            return response()->json($result);
        }
        else {
            $result['status'] = false;
            $result['error'] = 'Вам следует указать ИИН';
            return response()->json($result);
        }

    }

    public function infoByIIN(Request $request)
    {
        $result['status'] = false;

        $esbd = new ESBDController();
        $result_info = $esbd->checkIIN($request->iin);

        $result['status_code'] = $result_info;

        if(isset($result_info['code']) && ($result_info['code'] == '1005007' || $result_info['code'] == '1005008')){
            $result['status'] = true;

            $result_info['response'] = str_replace('grade','класс',$result_info['response']);

            $result['message'] = $result_info['response'];
            $result['grade'] = (int) filter_var($result['message'], FILTER_SANITIZE_NUMBER_INT);

            $result['unfinished_policy'] = 0;

            if($request->is_ogpo == 1 && isset($result_info['cost_policy']) && $result_info['cost_policy'] > 0){
                $result['unfinished_policy'] = 1;
                $result['total_cost'] = $result_info['cost_policy'];
                $result['ogpo_price'] = $result_info['cost_policy'];
                $result['kasko'] = 0;
                $result['ogpo_plus'] = 0;

                if(isset($result_info['actions']) && in_array(1,$result_info['actions'])){
                    $result['ogpo_price'] -= 50;
                    $result['kasko'] = 1;
                }

                if(isset($result_info['actions']) && in_array(2,$result_info['actions'])){
                    $result['ogpo_price'] -= 100;
                    $result['ogpo_plus'] = 1;
                }

                if($result['kasko'] == 0 && $result['ogpo_plus'] == 0){
                    $result['kasko'] = 1;
                    $result['total_cost'] += 50;
                }

                $result['transport_name'] = 'Ваш транспорт';
                $result['transport_number'] = '';
                if(isset($result_info['vehicles'][0]['transport_model'])){
                    $result['transport_name'] = $result_info['vehicles'][0]['transport_model'];
                }

                if(isset($result_info['vehicles'][0]['transport_number'])){
                    $result['transport_number'] = $result_info['vehicles'][0]['transport_number'];
                }
            }
        }
        elseif(isset($result_info['code']) && $result_info['code'] == '1005009'){
            $result['status'] = false;
            $result['error'] = 'ИИН принадлежит ребёнку';
        }
        elseif(isset($result_info['code']) && ($result_info['code'] == '1005001' || $result_info['code'] == '1005002')){
            $result['status'] = false;
            $result['error'] = 'не удалось найти клиента';
        }
        elseif(!isset($result_info['code'])){
            $result['status'] = false;
            $result['error'] = 'Некорректная работа сервера, попробуйте еще раз';
        }
        return $result;
    }

    public function getInfoByIIN(Request $request)
    {
        if($request->iin != ''){
            $result = $this->infoByIIN($request);
            return response()->json($result);
        }
    }

    public function infoCar(Request $request){
        if($request->auto_number != ''){
            $esbd = new ESBDController();
            $result_info = $esbd->getInfoAuto(2,$request->auto_number);
        }
        elseif($request->passport_number != '') {
            $esbd = new ESBDController();
            $result_info = $esbd->getInfoAuto(1,$request->passport_number);
        }
        else {
            $result['status'] = false;
            $result['error'] = 'Ошибка данных';
        }


        if(isset($result_info['data']['Vehicle']['MARK'])){
            $result['status'] = true;
            $result['message'] = $result_info['data']['Vehicle']['MARK'].' '.$result_info['data']['Vehicle']['MODEL'];

            $result['auto']['transport_number'] = $result_info['data']['Vehicle']['REG_NUM'];
            $result['auto']['transport_model'] = $result_info['data']['Vehicle']['MARK'].' '.$result_info['data']['Vehicle']['MODEL'];
            $result['auto']['transport_year'] = $result_info['data']['Vehicle']['NYEAR'];
            $result['auto']['transport_region'] = $result_info['data']['Vehicle']['REGION_ID'];
            $result['auto']['transport_vin'] = $result_info['data']['Vehicle']['VIN'];

        }
        else {
            $result['status'] = false;
            $result['status_code'] = $result_info;
            $result['error'] = 'Не удалось получить данные авто';
        }
        return $result;
    }

    public function getInfoCar(Request $request)
    {
        $result = $this->infoCar($request);
        return response()->json($result);
    }

    public function addDriver(Request $request)
    {
        if($request->iin != ''){
            $result = $this->infoByIIN($request);

            if($result['status'] == true){
                $row['name'] = $result['message'];
                $row['iin'] = $request->iin;
                $int = (int) filter_var($result['message'], FILTER_SANITIZE_NUMBER_INT);
                $row['grade'] = $int;

                return  view('index.calculate.content.driver-info',
                    [
                        'row' => $row
                    ]);
            }
            else return response()->json($result);
        }
    }

    public function addCar(Request $request)
    {
        if($request->transport_number != ''){
            $esbd = new ESBDController();
            $result_info = $esbd->getInfoAuto(2,$request->transport_number);

            if(isset($result_info['data']['Vehicle']['MARK'])) {
                $row['model_name'] = $result_info['data']['Vehicle']['MARK'] . ' ' . $result_info['data']['Vehicle']['MODEL'];
                $row['transport_number'] = $result_info['data']['Vehicle']['REG_NUM'];
                $row['transport_model'] = $result_info['data']['Vehicle']['MARK'].' '.$result_info['data']['Vehicle']['MODEL'];
                $row['transport_year'] = $result_info['data']['Vehicle']['NYEAR'];
                $row['transport_region'] = $result_info['data']['Vehicle']['REGION_ID'];
                $row['transport_vin'] = $result_info['data']['Vehicle']['VIN'];

                return  view('index.calculate.content.car-info',
                    [
                        'row' => $row
                    ]);

            }
            else {
                $result['status'] = false;
                $result['error'] = "По этим данным ничего не найдено";
                return response()->json($result);
            }
        }
    }

    public function calculateKaskoExpress(Request $request)
    {
        if($request->iin != ''){
            if($request->grade < 3){
                $result['status'] = false;
                $result['error'] = "Ваш класс не подходит, чтобы купить этот полис";
            }
            else {
                $result['status'] = true;

                if($request->money == 300000){
                    $result['cost'] = 45000;
                }
                elseif($request->money == 400000){
                    $result['cost'] = 65000;
                }
                else {
                    $result['cost'] = 82000;
                }
            }
            return response()->json($result);
        }
    }

    public function calculateOGPO(Request $request)
    {
        if($request->iin != ''){
            $iin = $request->iin;

            $esbd = new ESBDController();

            //льгота у клиента
            if($request->is_has_discount == 'true'){
                $check_discount = $esbd->checkPrivilege($iin);

                if(!isset($check_discount['success'])){
                    $result['status'] = false;
                    $result['status2'] = $request->is_has_discount;
                    $result['status_code'] = $check_discount;
                    $result['error'] = $check_discount['message'];
                    return response()->json($result);
                }
            }

            $drivers = $this->getDriverList($request);

            //список транспортов
            $car = array();
            if(isset($request->transport_numbers)){
                $key = 0;
                foreach($request->transport_numbers as $count => $item){
                    if($request['transport_numbers'][$count] != ''){
                        $car[$key]['transport_number'] = $request['transport_numbers'][$count];
                        $car[$key]['transport_model'] = $request['transport_models'][$count];
                        $car[$key]['transport_year'] = $request['transport_years'][$count];
                        $car[$key]['transport_region'] = $request['transport_regions'][$count];
                        $car[$key]['transport_vin'] = $request['transport_vins'][$count];
                        $key++;

                        if($key == 1){
                            $result['transport_number'] = $car[0]['transport_number'];
                        }
                    }
                }
            }

            $esbd = new ESBDController();
            $cost_info = $esbd->getCostByCar($iin,$car);

            $result['ogpo_price'] = 0;
            $result['total_cost'] = 0;
            $result['kasko_price'] = 50;
            $result['ogpo_plus_price'] = 100;

            if(isset($cost_info['price'])){
                $result['ogpo_price'] = $cost_info['price'];
                $result['total_cost'] = $cost_info['price'] + $result['kasko_price'];
            }

            if(isset($cost_info['price'])){
                $cost_info = $esbd->setCostPolice($iin,$drivers,$cost_info['price'],1);

                if(isset($cost_info['cost'])){
                    $result['status'] = true;
                    $result['status_code'] = $cost_info;
                    $result['total_cost'] = $cost_info['cost'];

                    if(isset($car[0])) $result['transport_name'] = $car[0]['transport_model'].' '.$car[0]['transport_year'];
                    else $result['transport_name'] = '';
                }
                else {
                    $result['status'] = false;
                    $result['status_code'] = $cost_info;
                    $result['error'] = "Не удалось получить данные, попробуйте еще раз";
                }
            }
            else {
                $result['status'] = false;
                $result['status_code'] = $cost_info;
                $result['error'] = "Не удалось получить данные, попробуйте еще раз";
            }

            return response()->json($result);
        }
    }

    public function getDriverList(Request $request)
    {
        //список водителей
        $drivers = array();
        if(isset($request->iins)){
            $key = 0;
            foreach($request->iins as $count => $item){
                if($request['iins'][$count] != ''){
                    $drivers[$key]['second_inn'] = $request['iins'][$count];
                    $drivers[$key]['additional_info'] = '';
                    $drivers[$key]['additional_price'] = '';
                    $key++;
                }
            }
        }
        return $drivers;
    }

    public function payPolice(Request $request)
    {
        if($request->cost != ''){
            $esbd = new ESBDController();
            $res_date = $esbd->setStartDatePolicy($request->iin,$request->start_date,$request->policy_period);

            if($request->cost != $request->before_cost){
                $drivers = $this->getDriverList($request);

                $cost_info = $esbd->setCostPolice($request->iin,$drivers,$request->cost,$request->is_need_kasko,$request->is_need_ogpo_plus);

                $result['status_code_0'] = $cost_info;

                if(!isset($cost_info['cost'])){
                    $result['status'] = false;
                    $result['status_code'] = $cost_info;
                    $result['error'] = "Не удалось получить данные, попробуйте еще раз";
                    return response()->json($result);
                }
            }

            $policy_db = new Policy();
            $policy = $policy_db->addPolicуToDatabaseBeforePay($request);

            if($policy == false){
                $result['status'] = false;
                $result['error_code'] = "ошибка при добавлении полиса";
                $result['error'] = "Не удалось получить данные, попробуйте еще раз";
                return response()->json($result);
            }

            $request->policy_id = $policy->policy_id;
            $request->hash = $policy->hash;

            $paybox = new PayboxController();
            $result_payment = $paybox->payment($request);

            if($result_payment == false){
                $result['status'] = false;
                $result['error_code'] = $result_payment;
                $result['error'] = "Не удалось получить данные, попробуйте еще раз";
                return response()->json($result);
            }
            else {
                $result['status'] = true;
                $result['href'] = $result_payment;
                return response()->json($result);
            }
        }
    }

    public function confirmPay(Request $request,$hash,$policy_id)
    {
        if($policy_id > 0){
            $file = "log2.txt";
            $current = file_get_contents($file);

            $current .= $request;
            file_put_contents($file, $current);

            try {
                $policy = Policy::where('policy_id',$policy_id)->where('hash',$hash)->first();

                if($policy == null) abort(404);
                elseif($policy->is_pay == 1){
                    Auth::logout();
                    if(Auth::loginUsingId($policy->user_id)){
                        return redirect('/profile/policy');
                    }
                    else return redirect('/ogpo?error=Ошибка_при_авторизации');
                }

                $request->phone = $policy->phone;
                $request->iin = $policy->iin;
                $request->user_name = $policy->user_name;

                $user_db = new Users();
                $result_user = $user_db->addNewUser($request);
                if($result_user == false){
                    return redirect('/ogpo?error=Ошибка2');
                }

                $request->user_id = $result_user['user_id'];

                $policy->user_id = $request->user_id;
                $policy->hash = $policy->hash.'-0'.$policy->policy_id;
                $policy->pay_date = date('Y-m-d');
                $policy->is_pay = 1;
                $policy->save();

                $iin = $policy->iin;
                $cost = $policy->cost;

                $esbd = new ESBDController();
                $cost_info = $esbd->payPolice($iin,$cost);

                if(isset($cost_info['success']) || (isset($cost_info['code']) && $cost_info['code'] == '1009002')){
                    $pdf_info = $esbd->getPDF($iin);

                    if(isset($pdf_info['success'])){
                        $request->policy_number = $pdf_info['number_policy'];
                        $request->base_code = $pdf_info['pdf'];
                    }
                    else {
                        $result['status'] = false;
                        $result['error'] = $pdf_info['message'];;
                        return response()->json($result);
                    }

                    if(isset($cost_info['policy_start_date']) && $cost_info['policy_finish_date']){
                        $request->policy_start_date = $cost_info['policy_start_date'];
                        $request->policy_finish_date = $cost_info['policy_finish_date'];
                    }

                    $request->policy_id = $policy_id;

                    $policy_db = new Policy();
                    $result_db = $policy_db->updatePolicуAfterPay($request);

                    $phone = '7'.Helpers::changePhoneFormat($request->phone);
                    $message = "Полис оформлен! Скачай в личном кабинете на ffins.kz";

                    $data['is_new_user'] = $result_user['is_new_user'];
                    $data['name'] = $policy->user_name;
                    $data['phone'] = $policy['phone'];
                    $data['start_date'] = date('d.m.Y');

                    if($result_user['is_new_user'] == 1){
                        $message .= '. Ваш пароль: '.$result_user['password'];
                        $data['password'] = $result_user['password'];
                    }

                    //$result = Helpers::sendSMS($phone,$message);

                    $pdf_file = str_replace('/','',$result_db->pdf_file);

                    $platform = 'Freedom E-commerce product v1.0';
                    $UnisenderApi = new UnisenderApi('679qu8sjytxjt4s748bmgiqsxxc7j3qkkka7mj6y', 'UTF-8', 4, null, false, $platform);

                    /*$result = $UnisenderApi->sendEmail(
                        [   'email' => $result_db->email,
                            'sender_name' => 'Freedom Finance Insurance',
                            'sender_email' => 'mukhtarov@ffins.kz',
                            'subject' => 'Полис оформлен!',
                            'body' => Helpers::getEmailText($data),
                            'list_id' => '17072033',
                            'attachments[file.pdf]' => file_get_contents($pdf_file)
                        ]
                    );*/

                    Auth::logout();
                    if(Auth::loginUsingId($result_user['user_id'])){
                        return redirect('/profile/policy')->with('success', '1');
                    }

                    return redirect('/ogpo?error=Ошибка-на-сайте-или-API-1');
                }
                else {
                    Auth::logout();
                    if(Auth::loginUsingId($policy->user_id)){
                        return redirect('/profile/policy');
                    }
                }
            }
            catch(Exception $ex){

            }
        }
    }
}
