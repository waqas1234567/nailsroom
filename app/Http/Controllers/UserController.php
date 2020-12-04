<?php

namespace App\Http\Controllers;

use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\Facades\DataTables;

class UserController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }
    function index(){
       $users= User::all();
       return view('Users/index',compact('users'));

    }

    function ajaxuser(){
        $users= User::select('*','users.id as user_id')->leftJoin('subscriptions','users.id','=','subscriptions.user_id')->get();
        return Datatables::of($users)
            ->addIndexColumn()
            ->addColumn('is_cancel', function($row){

                if($row->is_premium==1){
                       return 'Tak';
                }else{
                        return 'Nie';
                }

            })
            ->addColumn('valid', function($row){
                if(isset($row->payment_date)){
                    $date=explode(' ',$row->payment_date);

                    $time = strtotime($date[0]);
                    $final = date("Y-m-d", strtotime("+1 month", $time));

                    return $final;
                }else{

                    return '';
                }

            })
            ->addColumn('action', function($row){
                $btn = ' <a onclick="deleteUser('.$row->user_id.')"><img src="/public/assets/img/delete.png"></a>';

                return $btn;
            })
            ->rawColumns(['action'])
            ->make(true);
        return view('Users/index');
    }

    function destroy($id){


        $check=db::table('users')->where('id',$id)->delete();
        if($check==1){
            return redirect('/uzytkownicy')->with(['success'=>"
Użytkownik został pomyślnie usunięty !"]);
        }else{
            return redirect('/uzytkownicy')->with(['error'=>"Something went wrong !"]);
        }

    }


}
