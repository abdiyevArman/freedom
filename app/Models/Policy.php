<?php

namespace App\Models;

use App\Http\Helpers;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use DB;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class Policy extends Model
{
    protected $table = 'policy';
    protected $primaryKey = 'policy_id';

    /*use SoftDeletes;
    protected $dates = ['deleted_at'];*/

    public function addPolicуToDatabaseBeforePay(Request $request)
    {
        $policy = new Policy();
        $policy->iin = $request->iin;
        $policy->transport_number = $request->transport_number;
        $policy->transport_name = $request->transport_name;
        $policy->phone = $request->phone;
        $policy->email = $request->email;
        $policy->user_name = $request->user_name;
        $policy->is_pay = 0;
        $policy->cost = $request->cost;
        $policy->hash = md5(uniqid(time(), true));

        try {
            $policy->save();

            if($request->is_need_kasko > 0){
                $policy_product = new PolicyProduct();
                $policy_product->policy_id = $policy->policy_id;
                $policy_product->product_id = 1;
                $policy_product->save();
            }

            if($request->is_need_ogpo_plus > 0){
                $policy_product = new PolicyProduct();
                $policy_product->policy_id = $policy->policy_id;
                $policy_product->product_id = 2;
                $policy_product->save();
            }

            return $policy;

        }
        catch(Exception $ex){
            return false;
        }
    }

    public function updatePolicуAfterPay(Request $request)
    {
        $decoded = base64_decode($request->base_code);

        $hash = strtolower(Str::random(6));

        $file = $request->user_id."_".$request->policy_number.'.pdf';

        $hash .= '/'.$file;

        $dir = date('Y').'/'.date('m').'/'.date('d');

        $file = 'file/'.$dir.'/'.$file;

        if (!is_dir('file/'.$dir)) {
            mkdir('file/' . $dir,0777, true);
        }

        file_put_contents($file, $decoded);

        $policy = Policy::where('policy_id',$request->policy_id)->first();
        if($policy == null) return false;

        $policy->pdf_file = '/'.$file;
        $policy->pdf_hash_url = '/policy/'.$hash;
        $policy->base_code = $request->base_code;
        $policy->policy_number = $request->policy_number;
        $policy->is_success = 1;
        $policy->is_pay = 1;
        if(isset($request->policy_start_date) && isset($request->policy_finish_date)){
            $policy->policy_start_date = $request->policy_start_date;
            $policy->policy_finish_date = $request->policy_finish_date;
        }

        try {
            $policy->save();

            return $policy;

        }
        catch(Exception $ex){
            return false;
        }
    }
}
