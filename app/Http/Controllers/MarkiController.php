<?php

namespace App\Http\Controllers;

use App\brand;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\Facades\DataTables;

class MarkiController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */



    public function __construct()
    {
        $this->middleware('auth');



    }

    public function index(Request $request)
    {
              $role=Auth::user()->role;

              return view('marki/Marki-Kolory',compact('role'));

    }


    public function ajaxMarki(Request $request){

        if(Auth::user()->role=='Super Admin'){
            $brands = DB::select("SELECT  brands.*,(SELECT count(collections.id) FROM collections where collections.brand_id=brands.id) as collections FROM brands order by brands.id desc");
            return Datatables::of($brands)
                ->addIndexColumn()
                ->addColumn('action', function($row){

                    $btn = ' <a data-toggle="tooltip" title="Kolekcji" style="margin-right:1rem" href="/marki/kolekcje/'.$row->id.'"><img src="/public/assets/img/gear.png"></a>
                             <a data-toggle="tooltip" title="wzór" style="margin-right:1rem" href="/marki/wzór/'.$row->id.'"><img height="30px" width="30px" src="/public/img/pattern.png"></a>
                            <a style="margin-right:1rem" data-toggle="tooltip" title="edytuj markę" href="/marki/edit-marki/'.$row->id.'"><img src="/public/assets/img/Vector.png"></a>
                            <a onclick="deleteMarki('.$row->id.')" data-toggle="tooltip" title="usuń markę" href="#"><img src="/public/assets/img/delete.png"></a>';

                    return $btn;
                })
                ->rawColumns(['action'])
                ->make(true);

        }else{
            $brands = DB::select("SELECT  brands.*,(SELECT count(collections.id) FROM collections where collections.brand_id=brands.id) as collections FROM brands where brands.user_id=".Auth::user()->id." order by brands.id desc");
            return Datatables::of($brands)
                ->addIndexColumn()
                ->addColumn('action', function($row){


                    $btn = ' <a data-toggle="tooltip" title="Kolekcji" style="margin-right:1rem" href="/marki/kolekcje/'.$row->id.'"><img src="/public/assets/img/gear.png"></a>
                             <a data-toggle="tooltip" title="wzór" style="margin-right:1rem" href="/marki/wzór/'.$row->id.'"><img height="30px" width="30px" src="/public/img/pattern.png"></a>

                            <a style="margin-right:1rem" data-toggle="tooltip" title="edytuj markę" href="/marki/edit-marki/'.$row->id.'"><img src="/public/assets/img/Vector.png"></a>
                            <a onclick="deleteMarki('.$row->id.')" data-toggle="tooltip" title="usuń markę" href="#"><img src="/public/assets/img/delete.png"></a>';

                    return $btn;
                })
                ->rawColumns(['action'])
                ->make(true);
        }

        $role=Auth::user()->role;


        if(isset($brands)){
            return view('marki/Marki-Kolory');

        }else{

            abort(404);
        }
    }


    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //load marki create view

         return view('marki/add-marki');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->validate($request, [
            'brand' => 'required',
        ]);
           $image='';
        if ($request->hasFile('brand_image')){
            $imageName = time().rand(10,1000).'.'.$request->brand_image->extension();
            $request->brand_image->move(public_path('/img'), $imageName);

            $image=url('/public/img/'. $imageName);

        }
        $input['name']=$request->brand;
        if(isset($image) && !empty($image)){
            $input['brand_image']=$image;
        }else{
            $input['brand_image']='';
        }
        if(Auth::user()->role!='Super Admin'){
            $input['user_id']=Auth::user()->id;
        }else{
            $input['user_id']=0;
        }

        brand::create($input);
        return redirect('/marki')->with(['success'=>'marka dodana pomyślnie !']);
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
          $brand = brand::find($id);
          return view('marki/edit-marki',compact('brand'));
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
            'brand' => 'required',

        ]);

        if ($request->hasFile('brand_image')){
            $imageName = time().rand(10,1000).'.'.$request->brand_image->extension();
            $request->brand_image->move(public_path('/img'), $imageName);

            $image=url('/public/img/'. $imageName);
            $input['brand_image']=$image;
        }
        $input['name']=$request->brand;

        brand::where('id',$request->id)->update($input);
        return redirect('/marki')->with(['success'=>'marka zaktualizowana pomyślnie !']);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        brand::where('id',$id)->delete();
        return redirect('/marki')->with(['success'=>'marka została pomyślnie usunięta !']);
    }
}
