<?php

namespace App\Http\Controllers\Index;

use App\Http\Helpers;
use App\Models\Arbitrator;
use App\Models\Blog;
use App\Models\Brand;
use App\Models\Category;
use App\Models\Contact;
use App\Models\Menu;
use App\Models\News;
use App\Models\Order;
use App\Models\Page;


use App\Models\Product;
use App\Models\Question;
use App\Models\Review;
use App\Models\Service;
use App\Models\Subscription;
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



class ESBDController extends Controller
{
    public $lang = 'ru';

    public function __construct()
    {
        $this->lang = Helpers::getSessionLang();
    }


    public $api_key = '64ca47a6e8bd6ca070b9749e6faafd4698dd0e98cb12fcf7f05cd0294e9c765b';
    public $login = 'admin';
    public $password = '123';

    public function authorization()
    {
        $c = curl_init();
        curl_setopt($c, CURLOPT_URL,"https://polis24.ffin.kz/insurance/api/ping-pong/");
        curl_setopt($c, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
        curl_setopt($c, CURLOPT_USERPWD, "test_srv_policy:899$%qX1!");
        curl_setopt($c, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($c, CURLOPT_SSL_VERIFYHOST, false);
        curl_setopt($c, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($c, CURLOPT_HTTPHEADER,
            array(
                'Api-key: ' . $this->api_key
            ));
        $res = curl_exec($c);
        $res_row =  json_decode($res);

        return $res;
    }

    public function checkIIN($iin)
    {
        $data['iin'] = str_replace(' ','',$iin);

        $c = curl_init();
        curl_setopt($c, CURLOPT_URL,"https://polis24.ffin.kz/insurance/api/get-client-data-by-iin");
        curl_setopt($c, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
        curl_setopt($c, CURLOPT_USERPWD, "test_srv_policy:899$%qX1!");
        curl_setopt($c, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($c, CURLOPT_SSL_VERIFYHOST, false);
        curl_setopt($c, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($c, CURLOPT_POSTFIELDS, $data);
        curl_setopt($c, CURLOPT_HTTPHEADER,
            array(
                'Api-key: ' . $this->api_key,
               // 'Api-iin: 920210302337'
            ));
        $res = curl_exec($c);
        $data = json_decode($res, TRUE);

        return $data;
    }

    public function checkIIN2($iin)
    {
        $data['iin'] = str_replace(' ','',$iin);
        
        $c = curl_init();
        curl_setopt($c, CURLOPT_URL,"https://polis24.ffin.kz/insurance/api/get-client-data-by-iin");
        curl_setopt($c, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
        curl_setopt($c, CURLOPT_USERPWD, "test_srv_policy:899$%qX1!");
        curl_setopt($c, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($c, CURLOPT_SSL_VERIFYHOST, false);
        curl_setopt($c, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($c, CURLOPT_POSTFIELDS, $data);
        curl_setopt($c, CURLOPT_HTTPHEADER,
            array(
                'Api-key: ' . $this->api_key,
                // 'Api-iin: 920210302337'
            ));
        $res = curl_exec($c);
        $data = json_decode($res, TRUE);

        return $data;
    }

    public function getInfoAuto($type,$word)
    {
        $data['word'] = str_replace(' ','',$word);
        $data['type'] = $type;

        $c = curl_init();
        curl_setopt($c, CURLOPT_URL,"https://polis24.ffin.kz/insurance/api/search-vehicle");
        curl_setopt($c, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
        curl_setopt($c, CURLOPT_USERPWD, "test_srv_policy:899$%qX1!");
        curl_setopt($c, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($c, CURLOPT_SSL_VERIFYHOST, false);
        curl_setopt($c, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($c, CURLOPT_POSTFIELDS, $data);
        curl_setopt($c, CURLOPT_HTTPHEADER,
            array(
                'Api-key: ' . $this->api_key
            ));
        $res = curl_exec($c);

        $data = json_decode($res, TRUE);

        return $data;
    }

    public function getCostByCar($iin,$cars)
    {
        $data = array(
            'data' => json_encode($cars,true)
        );

        $c = curl_init();
        curl_setopt($c, CURLOPT_URL,"https://polis24.ffin.kz/insurance/api/set-transport-submit-array");
        curl_setopt($c, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
        curl_setopt($c, CURLOPT_USERPWD, "test_srv_policy:899$%qX1!");
        curl_setopt($c, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($c, CURLOPT_SSL_VERIFYHOST, false);
        curl_setopt($c, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($c, CURLOPT_POSTFIELDS,$data);
        curl_setopt($c, CURLOPT_HTTPHEADER,
            array(
                'Api-key: ' . $this->api_key,
                'Api-iin: ' . $iin
            ));
        $res = curl_exec($c);

        $data = json_decode($res, TRUE);
        return $data;
    }

    public function getCostByCar2($iin,$data)
    {
        $c = curl_init();
        curl_setopt($c, CURLOPT_URL,"https://polis24.ffin.kz/insurance/api/set-transport-submit");
        curl_setopt($c, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
        curl_setopt($c, CURLOPT_USERPWD, "test_srv_policy:899$%qX1!");
        curl_setopt($c, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($c, CURLOPT_SSL_VERIFYHOST, false);
        curl_setopt($c, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($c, CURLOPT_POSTFIELDS,$data);
        curl_setopt($c, CURLOPT_HTTPHEADER,
            array(
                'Api-key: ' . $this->api_key,
                'Api-iin: ' . str_replace(' ','',$iin)
            ));
        $res = curl_exec($c);
        $data = json_decode($res, TRUE);


        return $data;
    }

    public function getCostByDrivers($iin,$iins)
    {
        $data = array(
            'data' => json_encode($iins,true),
            'iin' => $iin,
            'get_price' => 1
        );

        $c = curl_init();
        curl_setopt($c, CURLOPT_URL,"https://polis24.ffin.kz/insurance/api/get-client-data-by-iin");
        curl_setopt($c, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
        curl_setopt($c, CURLOPT_USERPWD, "test_srv_policy:899$%qX1!");
        curl_setopt($c, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($c, CURLOPT_SSL_VERIFYHOST, false);
        curl_setopt($c, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($c, CURLOPT_POSTFIELDS, $data);
        curl_setopt($c, CURLOPT_HTTPHEADER,
            array(
                'Api-key: ' . $this->api_key,
                'Api-iin: ' . str_replace(' ','',$iin)
            ));
        $res = curl_exec($c);

        $data = json_decode($res, TRUE);

        return $data;
    }

    public function setCostPolice($iin,$iins,$cost = 0,$is_need_kasko = 0, $is_need_agpo_plus = 0)
    {
        $data = array(
            'data' => json_encode($iins,true),
            'kasko' => strval($is_need_kasko),
            'ogpo_plus' => strval($is_need_agpo_plus),
            'cost' => $cost
        );

        $c = curl_init();
        curl_setopt($c, CURLOPT_URL,"https://polis24.ffin.kz/insurance/api/set-cost-polis-data/");
        curl_setopt($c, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
        curl_setopt($c, CURLOPT_USERPWD, "test_srv_policy:899$%qX1!");
        curl_setopt($c, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($c, CURLOPT_SSL_VERIFYHOST, false);
        curl_setopt($c, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($c, CURLOPT_POSTFIELDS, $data);
        curl_setopt($c, CURLOPT_HTTPHEADER,
            array(
                'Api-key: ' . $this->api_key,
                'Api-iin: ' . str_replace(' ','',$iin)
            ));
        $res = curl_exec($c);

        $data = json_decode($res, TRUE);
        return $data;
    }

    public function payPolice($iin,$pay)
    {
        $data = array(
            'device_type' => 2,
            'method' => 2,
            'trm_guid' => 'cccb913c-6d56-'.rand(1000,9999).'-'.rand(1000,9999).'-143dff3b8ed2',
            'pay' => (int) $pay,
        );

        $c = curl_init();
        curl_setopt($c, CURLOPT_URL,"https://polis24.ffin.kz/insurance/api/set-cost-polis-submit");
        curl_setopt($c, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
        curl_setopt($c, CURLOPT_USERPWD, "test_srv_policy:899$%qX1!");
        curl_setopt($c, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($c, CURLOPT_SSL_VERIFYHOST, false);
        curl_setopt($c, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($c, CURLOPT_POSTFIELDS, $data);
        curl_setopt($c, CURLOPT_HTTPHEADER,
            array(
                'Api-key: ' . $this->api_key,
                'Api-iin: ' . str_replace(' ','',$iin)
            ));
        $res = curl_exec($c);

        $data = json_decode($res, TRUE);
        return $data;
    }

    public function getPDF($iin)
    {
        $data = array(
            'type' => 1,
        );

        $c = curl_init();
        curl_setopt($c, CURLOPT_URL,"https://polis24.ffin.kz/insurance/api/set-polis-print-stream");
        curl_setopt($c, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
        curl_setopt($c, CURLOPT_USERPWD, "test_srv_policy:899$%qX1!");
        curl_setopt($c, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($c, CURLOPT_SSL_VERIFYHOST, false);
        curl_setopt($c, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($c, CURLOPT_POSTFIELDS, $data);
        curl_setopt($c, CURLOPT_HTTPHEADER,
            array(
                'Api-key: ' . $this->api_key,
                'Api-iin: ' . str_replace(' ','',$iin)
            ));
        $res = curl_exec($c);

        $data = json_decode($res, TRUE);
        return $data;
    }

    public function checkPrivilege($iin)
    {
        $data['iin'] = str_replace(' ','',$iin);
        $data['privilege'] = true;

        $c = curl_init();
        curl_setopt($c, CURLOPT_URL,"https://polis24.ffin.kz/insurance/api/set-facilities-for-policyholder");
        curl_setopt($c, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
        curl_setopt($c, CURLOPT_USERPWD, "test_srv_policy:899$%qX1!");
        curl_setopt($c, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($c, CURLOPT_SSL_VERIFYHOST, false);
        curl_setopt($c, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($c, CURLOPT_POSTFIELDS, $data);
        curl_setopt($c, CURLOPT_HTTPHEADER,
            array(
                'Api-key: ' . $this->api_key,
                // 'Api-iin: 920210302337'
            ));
        $res = curl_exec($c);
        $data = json_decode($res, TRUE);

        return $data;
    }

    public function setStartDatePolicy($iin,$start_date,$policy_period = 12)
    {
        $data['iin'] = str_replace(' ','',$iin);
        $data['season'] = $policy_period;

        $timestamp = strtotime($start_date);
        $data['start_date'] = date("Y-m-d", $timestamp);


        $c = curl_init();
        curl_setopt($c, CURLOPT_URL,"https://polis24.ffin.kz/insurance/api/set-season-policy");
        curl_setopt($c, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
        curl_setopt($c, CURLOPT_USERPWD, "test_srv_policy:899$%qX1!");
        curl_setopt($c, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($c, CURLOPT_SSL_VERIFYHOST, false);
        curl_setopt($c, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($c, CURLOPT_POSTFIELDS, $data);
        curl_setopt($c, CURLOPT_HTTPHEADER,
            array(
                'Api-key: ' . $this->api_key,
                // 'Api-iin: 920210302337'
            ));
        $res = curl_exec($c);
        $data = json_decode($res, TRUE);

        return $data;
    }

    public function checkValidatePolicy($iin)
    {
        $data = array(
            'data' => '{"iin":"'.$iin.'"}',
        );

        $c = curl_init();
        curl_setopt($c, CURLOPT_URL,"https://polis24.ffin.kz/insurance/api/check-valid-policies");
        curl_setopt($c, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
        curl_setopt($c, CURLOPT_USERPWD, "test_srv_policy:899$%qX1!");
        curl_setopt($c, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($c, CURLOPT_SSL_VERIFYHOST, false);
        curl_setopt($c, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($c, CURLOPT_POSTFIELDS, $data);
        curl_setopt($c, CURLOPT_HTTPHEADER,
            array(
                'Api-key: ' . $this->api_key,
                'Api-iin: ' . str_replace(' ','',$iin)
            ));
        $res = curl_exec($c);

        $data = json_decode($res, TRUE);
        return $data;
    }
}
