<?php

namespace App\Http\Controllers;

use App\brand;
use App\collections;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class CollectionController extends Controller
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
          return view('collections/add-collection',compact('id',$id));
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
            'collection' => 'required',
        ]);
        $input['name']=$request->collection;
        $input['brand_id']=$request->id;



        collections::create($input);
        return redirect('/marki/kolekcje/'.$request->id)->with(['success'=>'kolekcja została dodana pomyślnie!']);

    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        /**
             * It will find all collection for a particular brand
         */


        $collections = brand::find($id)->collections;
        return view('collections/index',compact('collections'));
    }


    public function ajaxKolekcje(request $request,$id){



        $collections = brand::find($id)->collections;
        return Datatables::of($collections)
            ->addIndexColumn()
            ->addColumn('action', function($row){

                $btn = '<a data-toggle="tooltip" title="zabarwienie" style="margin-right:1rem" href="/marki/kolor/'.$row->id.'"><img src="/public/assets/img/color.png"></a>
                            <a style="margin-right:1rem" data-toggle="tooltip" title="edytuj kolekcje" href="/marki/edytuj-kolekcje/'.$row->id.'"><img src="/public/assets/img/Vector.png"></a>
                            <a onclick="deleteCollection('.$row->id.','.$row->brand_id.')" data-toggle="tooltip" title="usuń kolekcje" href="#"><img src="/public/assets/img/delete.png"></a>';

                return $btn;
            })
            ->rawColumns(['action'])
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
        $collection = collections::find($id);
        return view('collections/edit-collection',compact('collection'));
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
            'collection' => 'required',
        ]);
        $input['name']=$request->collection;
        collections::where('id',$request->id)->update($input);
        return redirect('/marki/kolekcje/'.$request->brand_id)->with(['success'=>'kolekcja została zaktualizowana pomyślnie !']);

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id,$brand_id)
    {
        collections::where('id',$id)->delete();
        return redirect('/marki/kolekcje/'.$brand_id)->with(['success'=>'kolekcja została pomyślnie usunięta !']);
    }
}
