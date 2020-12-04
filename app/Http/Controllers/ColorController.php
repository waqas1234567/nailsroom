<?php

namespace App\Http\Controllers;

use App\brand;
use App\collections;
use App\colors;
use Illuminate\Http\Request;

class ColorController extends Controller
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
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create($id)
    {
              $colors = collections::find($id)->colors;
              $collection=collections::find($id);
              $brand=brand::find($collection->brand_id);
        return view('colors/create-kolor',compact('collection','brand','colors'));
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

             'code'=>'required',
             'name'=>'required',
             'icon' => 'image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);

        if ($request->hasFile('icon')){
            $imageName = time().rand(10,1000).'.'.$request->icon->extension();
            $request->icon->move(public_path('/img'), $imageName);

            $image=url('/public/img/'. $imageName);


        }

        $input['name']=$request->name;
        $input['code']=$request->code;
        $input['collections_id']=$request->collection_id;
        if(isset($image)){
            $input['icon']=$image;
        }else{
            $input['icon']='';
        }


        colors::create($input);
        return redirect()->back()->with(['success'=>'Kolor został dodany pomyślnie !']);

    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {

        return view('colors/index',compact('colors'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
           colors::find($id)->delete();
        return redirect()->back()->with(['success'=>'
Kolor został pomyślnie usunięty !']);

    }
}
