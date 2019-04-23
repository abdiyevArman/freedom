<?php

namespace App\Http\Controllers\Index;

use App\Http\Helpers;
use App\Models\Contact;
use App\Models\Menu;
use App\Models\News;
use App\Models\Order;
use App\Models\Page;


use App\Models\Policy;
use App\Models\Product;
use App\Models\Question;
use App\Models\RequestReject;
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



class PolicyController extends Controller
{
    public $lang = 'ru';

    public function __construct()
    {
        $this->lang = Helpers::getSessionLang();
    }
    
    public function rejectPolicy(Request $request){
        $validator = Validator::make($request->all(), [
            'policy_id' => 'required'
        ]);

        if ($validator->fails()) {
            $messages = $validator->errors();
            $error = $messages->all();
            $result['status'] = false;
            $result['error'] = 'Вам следует указать необходимые данные';
            return $result;
        }

        $policy = Policy::where('user_id',Auth::user()->user_id)->where('policy_id',$request->policy_id)->first();

        if($policy == null){
            $result['status'] = false;
            $result['error'] = 'Такого полиса не существует';
            return $result;
        }

        $request_db = new RequestReject();
        $request_db->policy_id = $request->policy_id;
        $request_db->save();

        $result['status'] = true;
        $result['message'] = 'Успешно отправлено';

        return response()->json($result);
    }
}
