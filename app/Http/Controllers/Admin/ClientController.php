<?php

namespace App\Http\Controllers\Admin;

use App\Http\Helpers;
use App\Models\Actions;
use App\Models\Cathedra;
use App\Models\Role;
use App\Models\Users;
use App\Models\Faculty;
use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;
use View;
use DB;
use Auth;

class ClientController extends Controller
{
    public function __construct()
    {
        $this->middleware('admin');
        View::share('menu', 'client');

        $roles = Role::orderBy('role_id','asc')->get();
        View::share('roles', $roles);
    }

    public function index(Request $request)
    {
        $row = Users::leftJoin('role','role.role_id','=','users.role_id')
                        ->orderBy('users.user_id','desc')
                       ->select('*',
                                 DB::raw('DATE_FORMAT(users.created_at,"%d.%m.%Y %H:%i") as date'));

        if(isset($request->active))
            $row->where('users.is_ban',$request->active);
        else $row->where('users.is_ban','0');
        
        if(isset($request->client_name) && $request->client_name != ''){
            $row->where(function($query) use ($request){
                $query->where('name','like','%' .$request->client_name .'%');
            });
        }

        if(isset($request->email) && $request->email != ''){
            $row->where(function($query) use ($request){
                $query->where('email','like','%' .$request->email .'%');
            });
        }

        if(isset($request->role) && $request->role != ''){
            $row->where(function($query) use ($request){
                $query->where('role_name_ru','like','%' .$request->role .'%');
            });
        }

        $row = $row->paginate(20);

        return  view('admin.client.client',[
            'row' => $row,
            'request' => $request
        ]);
    }

    public function create()
    {
        $row = new Users();
        $row->avatar = '/admin/img/avatar.jpg';
        
        return  view('admin.client.client-edit', [
            'title' => 'Добавить пользователя',
            'row' => $row
        ]);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'email' => 'required|email|unique:users,email,NULL,user_id,deleted_at,NULL'
        ]);

        if ($validator->fails()) {
            $messages = $validator->errors();
            $error = $messages->all();
            return  view('admin.client.client-edit', [
                'title' => 'Добавить пользователя',
                'row' => (object) $request->all(),
                'error' => $error[0]
            ]);
        }

        $client = new Users();
        $client->name = $request->name;
        $client->avatar = $request->avatar;
        $client->email = $request->email;
        $client->password = Hash::make('12345');
        $client->role_id = $request->role_id;
        $client->save();


        $action = new Actions();
        $action->action_code_id = 2;
        $action->action_comment = 'users';
        $action->action_text_ru = 'добавил(а) пользователя "' .$client->name .'"';
        $action->user_id = Auth::user()->user_id;
        $action->universal_id = $client->user_id;
        $action->save();

        return redirect('/admin/client');
    }

    public function edit($id)
    {
        $row = Users::find($id);
     
        return  view('admin.client.client-edit', [
            'title' => 'Редактировать данные пользователя',
            'row' => $row
        ]);
    }

    public function show(Request $request,$id){

    }

    public function update(Request $request,$id)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'email' => 'required|unique:users,email,' .$id .',user_id'
        ]);

        if ($validator->fails()) {
            $messages = $validator->errors();
            $error = $messages->all();
            return  view('admin.client.client-edit', [
                'title' => 'Редактировать данные пользователя',
                'row' => (object) $request->all(),
                'error' => $error[0]
            ]);
        }

        $client = Users::find($id);
        $client->name = $request->name;
        $client->avatar = $request->avatar;
        $client->email = $request->email;
        $client->role_id = $request->role_id;
        $client->save();

        $action = new Actions();
        $action->action_code_id = 3;
        $action->action_comment = 'users';
        $action->action_text_ru = 'редактировал(а) данные пользователя "' .$client->name .'"';
        $action->user_id = Auth::user()->user_id;
        $action->universal_id = $client->user_id;
        $action->save();
        
        return redirect('/admin/client');
    }

    public function destroy($id)
    {
        $client = Users::find($id);
        $client->delete();

        $action = new Actions();
        $action->action_code_id = 1;
        $action->action_comment = 'users';
        $action->action_text_ru = 'удалил(а) пользователя "' .$client->name .'"';
        $action->user_id = Auth::user()->user_id;
        $action->universal_id = $id;
        $action->save();

    }

    public function resetPassword(Request $request,$user_id){
        $client = Users::find($user_id);
        $client->password = Hash::make('12345');
        $client->save();

        $action = new Actions();
        $action->action_comment = 'user';
        $action->action_text_ru = 'сбросил пароль пользователя "' .$client->name .'"';
        $action->action_code_id = 8;
        $action->user_id = Auth::user()->user_id;
        $action->universal_id = $client->user_id;
        $action->save();

        $url = '';
        if($request->page > 0) $url = '?page='.$request->page;
        return redirect('/admin/client'.$url);
    }
}
