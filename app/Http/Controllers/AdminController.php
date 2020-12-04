<?php

namespace App\Http\Controllers;

use App\admin;
use App\permissions;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Yajra\DataTables\Facades\DataTables;

class AdminController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {

            return view('Admin/index');
    }


    public function ajaxadmin(){
        $admins=admin::all();
        return Datatables::of($admins)
            ->addIndexColumn()

            ->addColumn('action', function($row){
                $btn = '<a style="margin-right:1rem" href="/admin/edytowac-administratora/'.$row->id.'"><img src="/public/assets/img/Vector.png"></a>
                    <a onclick="deleteAdmin('.$row->id.')"><img src="/public/assets/img/delete.png"></a>';

                return $btn;
            })
            ->rawColumns(['action'])
            ->make(true);
        return view('Admin/index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $permissions=permissions::all();
        return view('Admin/create',compact('permissions'));

    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {


      if($request->role=='Admin'){
          $this->validate($request, [
              'name' => 'required',
              'email' => 'required|email|unique:admins,email',
              'password' => 'required',
              'permission'=>'required',
              'role'=>'required'
          ]);
      }else{
          $this->validate($request, [
              'name' => 'required',
              'email' => 'required|email|unique:admins,email',
              'password' => 'required',
              'role'=>'required'

          ]);
      }



        $data=array(
            'name'=>$request->name,
            'email'=>$request->email,
            'password'=>Hash::make($request->password),
            'role'=>$request->role
        );

         $admin=admin::create($data);

         $permissions=permissions::all();


         if($request->role!='Admin'){
             foreach($permissions as $p){
                 $permission=array(
                     'admin_id'=>$admin->id,
                     'adminpermission_id'=>$p->id
                 );

                 DB::table('adminpermissions')->insert($permission);
             }


         }else{
             foreach($request->permission as $p){
                 $permission=array(
                     'admin_id'=>$admin->id,
                     'adminpermission_id'=>$p
                 );

                 DB::table('adminpermissions')->insert($permission);
             }
         }


         return redirect('/admin')->with(['success'=>'
Administrator został pomyślnie utworzony !']);

    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
       $admin= admin::where('id',$id)->first();
       $adminPermissions=DB::table('adminpermissions')->where('admin_id',$id)->get();
        $permissions=DB::table('permissions')->get();

        return view('Admin/edit',compact('admin','permissions','adminPermissions'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
        $this->validate($request, [
            'name' => 'required',
            'email' => 'required|email|unique:admins,email, '. $request->id . ',id',

        ]);

        $data=array(
            'name'=>$request->name,
            'email'=>$request->email,
        );

        $admin=admin::where('id',$request->id)->update($data);
        DB::table('adminpermissions')->where('admin_id',$request->id)->delete();
         if(isset($request->permission)) {


             foreach ($request->permission as $p) {
                 $permission = array(
                     'admin_id' => $request->id,
                     'adminpermission_id' => $p
                 );

                 DB::table('adminpermissions')->insert($permission);
             }
         }

        return redirect('/admin')->with(['success'=>'Admin updated successfully !']);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
         admin::where('id',$id)->delete();
        DB::table('adminpermissions')->where('admin_id',$id)->delete();
        return redirect('/admin')->with(['success'=>'
Administrator został pomyślnie usunięty !']);


    }
}
