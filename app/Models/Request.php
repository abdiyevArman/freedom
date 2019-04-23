<?php

namespace App\Models;

use App\Http\Helpers;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;


class Request extends Model
{
    protected $table = 'request';
    protected $primaryKey = 'request_id';

    use SoftDeletes;
    protected $dates = ['deleted_at'];

    public function checkExistRequestByUser($user_id){
        $row = Request::where('user_id','=',$user_id)
            ->where('status_id','=',1)
            ->first();
        return $row;
    }

    public function checkExistRequestBySession($session){
        $row = Request::where('session','=',$session)
            ->where('status_id','=',1)
            ->first();
        return $row;
    }

    public function getProductCountInBasket(){
        $user_id = Helpers::getUserId();
        $session = csrf_token();
      
        if($user_id > 0){
            $row = Request::where('user_id','=',$user_id)
                ->where('status_id','=',1)
                ->first();
        }
        else {
            $row = Request::where('session','=',$session)
                ->where('status_id','=',1)
                ->first();
        }

        $count = 0;

        if($row != null){
            $count = RequestProduct::where('request_id',$row->request_id)->count();
        }

        return $count;
    }

    public function getProductListInBasket(){
        $user_id = Helpers::getUserId();
        $session = csrf_token();

        if($user_id > 0){
            $row = Request::where('user_id','=',$user_id)
                ->where('status_id','=',1)
                ->first();
        }
        else {
            $row = Request::where('session','=',$session)
                ->where('status_id','=',1)
                ->first();
        }

        $product_list['list'] = array();
        $product_list['sum'] = array();
        $product_list['sum'] = 0;

        if($row != null){
            $product_list['list'] = RequestProduct::leftJoin('product','product.product_id','=','request_product.product_id')->where('request_id',$row->request_id)->get();
            $product_list['sum'] = RequestProduct::where('request_id',$row->request_id)->sum('price');
        }

        return $product_list;
    }
}
