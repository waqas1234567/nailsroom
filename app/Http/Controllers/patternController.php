<?php

namespace App\Http\Controllers;

use App\brand;
use App\pattren;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\Facades\DataTables;

class patternController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('pattern/create');
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
            'image' => 'mimes:png|required|max:10000|dimensions:max_width=100,max_height=84',
            'background' => 'required',
        ]);
        if ($request->hasFile('image')){
            $imageName = time().rand(10,1000).'.'.$request->image->extension();
            $request->image->move(public_path('/img'), $imageName);

            $image=url('/public/img/'. $imageName);

        }

        db::table('pattern')->insert(['image'=>$image,'background'=>$request->background,'brand_id'=>$request->brand_id]);
        return redirect('/marki/wzór/'.$request->brand_id)->with(['success'=>'wzór dodana pomyślnie !']);


    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {

        return view('pattern/index');


    }



    public function ajaxpattern(request $request,$id){

        $collections = db::table('pattern')->where('brand_id',$id)->get();
        return Datatables::of($collections)
            ->addColumn('logo', function($row){

                $logo = '<a><img width="50px" height="50px" src="'.$row->image.'"></a>';

                return $logo;
            })
            ->addColumn('background', function($row){

                  $color='<span style=" height: 25px;
  width: 25px;
  background-color: '.$row->background.';
  border-radius: 50%;
  display: inline-block;"></span>';

                return $color;
            })
            ->addColumn('action', function($row){

                $btn = '
                            <a style="margin-right:1rem" data-toggle="tooltip" title="edytuj wzór" href="/marki/edytuj-wzór/'.$row->id.'"><img src="/public/assets/img/Vector.png"></a>
                            <a onclick="deletePattern('.$row->id.','.$row->brand_id.')" data-toggle="tooltip" title="usuń wzór" href="#"><img src="/public/assets/img/delete.png"></a>';

                return $btn;
            })

            ->rawColumns(['action','logo','background'])
            ->make(true);

    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $pattern = pattren::find($id);
        return view('pattern/edit',compact('pattern'));
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

//        dd($request->all());
        $this->validate($request, [
            'background' => 'required',
        ]);
        if ($request->hasFile('image')){
            $imageName = time().rand(10,1000).'.'.$request->image->extension();
            $request->image->move(public_path('/img'), $imageName);

            $image=url('/public/img/'. $imageName);
            db::table('pattern')->where('id',$request->id)->update(['image'=>$image,'background'=>$request->background]);

        }else{
            db::table('pattern')->where('id',$request->id)->update(['background'=>$request->background]);

        }

        return redirect('/marki/wzór/'.$request->brand_id)->with(['success'=>'wzorzec został pomyślnie zaktualizowany !']);


    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id,$brand_id)
    {
          db::table('pattern')->where('id',$id)->delete();

        return redirect('/marki/wzór/'.$brand_id)->with(['success'=>'wzór usunięty pomyślnie !']);
    }
}
