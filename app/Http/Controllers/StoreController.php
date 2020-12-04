<?php

namespace App\Http\Controllers;

use App\brand;
use App\collections;
use App\store;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\Facades\DataTables;

class StoreController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index(){

//        if(Auth::user()->role=='Super Admin') {
//            $stores = store::with('brand')->orderBy('stores.id', 'desc')->get();
//        }else{
//            $stores = store::with('brand')->where('user_id',Auth::user()->id)->orderBy('stores.id', 'desc')->get();
//        }
        $role=Auth::user()->role;


            return view('stores/index',compact('role'));


    }

    function ajaxsklepy(request $request){
        if(Auth::user()->role=='Super Admin') {
            $stores = store::with('brand')->orderBy('stores.id', 'desc')->get();
            return Datatables::of($stores)
                ->addIndexColumn()
                ->addColumn('brands', function($row){
                        $brand_array=array() ;
                        foreach($row->brand as $b){
                            array_push($brand_array,$b->name);

                        }

                        $brand= implode(' / ', $brand_array);
                        return $brand;
                })
                ->addColumn('action', function($row){

                    $btn = '  <a style="margin-right:1rem" href="/sklepy/edytować-sklepy/'.$row->id.'"><img src="/public/assets/img/Vector.png"></a>
                        <a onclick="deleteStore('.$row->id.')"><img src="/public/assets/img/delete.png"></a>';

                    return $btn;
                })
                ->rawColumns(['brands'])
                ->rawColumns(['action'])
                ->make(true);
        }else{
            $stores = store::with('brand')->where('user_id',Auth::user()->id)->orderBy('stores.id', 'desc')->get();
            return Datatables::of($stores)
                ->addIndexColumn()
                ->addColumn('brands', function($row){
                    $brand_array=array() ;
                    foreach($row->brand as $b){
                        array_push($brand_array,$b->name);

                    }

                    $brand= implode(' / ', $brand_array);
                    return $brand;
                })
                ->addColumn('action', function($row){

                    $btn = '  <a style="margin-right:1rem" href="/sklepy/edytować-sklepy/'.$row->id.'"><img src="/public/assets/img/Vector.png"></a>
                        <a onclick="deleteStore('.$row->id.')"><img src="/public/assets/img/delete.png"></a>';

                    return $btn;
                })
                ->rawColumns(['brands'])
                ->rawColumns(['action'])
                ->make(true);

        }


        return view('stores/index');

    }



    public function create(Request $request){

        $brand=brand::all();
        $request->session()->forget('brands_collection');
        return view('stores/create-store',compact('brand'));
    }




    public function store(Request $request){

        $rules = [
            'name' => 'required',
            'street' => 'required',
            'place' => 'required',
            'latitude' => 'required',
            'longitude' => 'required',
            'phone' => 'required',
            'web_page' => 'required',
            'brands'=>'required',
            'email'=>'required|string|email|max:255|unique:stores',
            'des'=>'required',
            'logo'=>'required|image|mimes:jpeg,png,jpg|max:2048'

        ];

        $customMessages = [
            'required' => 'The :attribute field is required.'
        ];

        $this->validate($request, $rules, $customMessages);


        $value = $request->session()->get('brands_collection');
        $user_id=0;

        if(Auth::user()->role!='Super Admin'){
             $user_id=Auth::user()->id;
        }

        if ($request->hasFile('logo')){
            $imageName = time().rand(10,1000).'.'.$request->logo->extension();
            $request->logo->move(public_path('/img'), $imageName);

            $image=url('/public/img/'. $imageName);

        }

        $data=array(
                'name'=>$request->name,
                'street'=>$request->street,
                'place'=>$request->place,
                'latitude'=>$request->latitude,
                'longitude'=>$request->longitude,
                'phone'=>$request->phone,
                'web_page'=>$request->web_page,
                'apartment_no'=>$request->apartment_no,
                'email'=>$request->email,
                 'des'=>$request->des,
                 'user_id'=>$user_id,
                 'logo'=>$image

            );

            $stores=DB::table('stores')->insertGetId($data);

            foreach($request->brands as $b){

                $brand=array(
                    'brand_id'=>$b,
                     'store_id'=>$stores
                );
               $brand_store= DB::table('brand_store')->insertGetId($brand);

            }
            if(isset($value)){
                foreach($value as $v){
                    $collectionCheck = DB::table('brand_store_collections')->where([['collection_id',$v],['store_id',$stores]])->get();
                    if($collectionCheck->count()==0){
                        $brand=array(
                            'brand_store_id'=>0,
                            'collection_id'=>$v,
                            'store_id'=>$stores
                        );
                        DB::table('brand_store_collections')->insertGetId($brand);
                    }

                }
            }

             $request->session()->forget('brands_collection');
            return redirect('/sklepy')->with(['success'=>'sklep dodany pomyślnie!']);




    }

    public function getCollections(Request $request){


           $collections=collections::where('brand_id',$request->id)->get();
           $html='';
           $html.='<ul class="list-group">';
         $value = $request->session()->get('brands_collection');

//         dd($value);

           if(isset($collections)) {
               foreach ($collections as $c) {
                   $check = 0;
                   if (isset($value)) {
                       foreach ($value as $v) {
                           if ($c->id == $v) {
                               $check = 1;
                           }
                       }
                   }

                   if ($check == 1) {
                       $html .= '  <li class="list-group-item d-flex justify-content-between align-items-center mb-3">
                                <span><input  type="checkbox" name="collections[]" value="' . $c->id . '"  class="gridCheck1" checked><label style="top: -8px;">' . $c->name . '</label></span>
                            </li>';
                   } else {
                       $html .= '  <li class="list-group-item d-flex justify-content-between align-items-center mb-3">
                                <span><input  type="checkbox" name="collections[]" value="' . $c->id . '"  class="gridCheck1" ><label style="top: -8px;">' . $c->name . '</label></span>
                            </li>';
                   }


               }


               $html .= '</ul>';

           }else{
               $html='<p>Brak kolekcji do pokazania !</p>';
           }

              $response=array(
                  "html"=>$html,
                  'brand_id'=>$request->id
              );

        return response()->json($response,200);

}

function getCollectionsedit(Request $request){
    $collections=collections::where('brand_id',$request->id)->get();
    $html='';
    $html.='<ul class="list-group">';
    $value = DB::table('brand_store_collections')->where('store_id',$request->store_id)->get();
    ;


    foreach ($collections as $c){
        $check=0;
        if(isset($value)){
            foreach($value as $v) {
                if($c->id==$v->collection_id) {
                    $check=1;
                }
            }
        }

        if($check==1) {
            $html .= '  <li class="list-group-item d-flex justify-content-between align-items-center mb-3">
                                <span><input  type="checkbox" name="collections[]" value="' . $c->id . '"  class="gridCheck1" checked><label style="top: -8px;">' . $c->name . '</label></span>
                            </li>';
        }else{
            $html .= '  <li class="list-group-item d-flex justify-content-between align-items-center mb-3">
                                <span><input  type="checkbox" name="collections[]" value="' . $c->id . '"  class="gridCheck1" ><label style="top: -8px;">' . $c->name . '</label></span>
                            </li>';
        }


    }


    $html.='</ul>';


    $response=array(
        "html"=>$html,
        'brand_id'=>$request->id
    );

    return response()->json($response,200);
}

function saveCollections(Request $request){




       if(isset($request->deletedValue)){



           $products=session()->pull('brands_collection', []);

          echo 'before';
           echo '<pre>';
           print_r($products);


                    foreach($request->deletedValue as $d){

                        $key = array_search($d, $products);
                        unset($products[$key]);

                    }
           session()->put('brands_collection', $products);

       }
    $newProducts= session()->get('brands_collection');
    echo 'after';
    echo '<pre>';
    print_r($newProducts);
        $i=0;

        $collections=session()->get('brands_collection');
       if(isset($request->data)){
           foreach($request->data as $r){

              if(isset($collections)){
                  $key = array_search( $r['value'], $collections);

                  if(empty($key)){
                      $request->session()->push('brands_collection', $r['value']);

                  }
              }else{
                  $request->session()->push('brands_collection', $r['value']);

              }


           }
       }
//
//    echo 'after';
//    echo '<pre>';
//    print_r(session()->get('brands_collection'));

}


      function editCollections(Request $request){
            if(isset($request->data)){
                $i=0;
                foreach($request->data as $collection){

                    if($i!=0) {
                        $collectionCheck = DB::table('brand_store_collections')->where([['collection_id', $collection['value']], ['store_id', $request->data[0]['value']]])->get();

                        if ($collectionCheck->count() == 0) {

                            $data = array(
                                'store_id' => $request->data[0]['value'],
                                'collection_id' => $collection['value'],
                                'brand_store_id' => 0
                            );
                            DB::table('brand_store_collections')->insert($data);
                        }

                    }
                    $i++;

                }

            }
          if(isset($request->deletedValue)){
              foreach($request->deletedValue as $d){
                  $collectionCheck = DB::table('brand_store_collections')->where([['collection_id',$d],['store_id',$request->data[0]['value']]])->get();
                  if($collectionCheck->count()==1) {
                        DB::table('brand_store_collections')->where([['collection_id',$d],['store_id',$request->data[0]['value']]])->delete();

                  }

              }

          }


      }

function destroy($id){

           DB::table('stores')->where('id',$id)->delete();
           DB::table('brand_store')->where('store_id',$id)->delete();
           DB::table('brand_store_collections')->where('store_id',$id)->delete();
    return redirect('/sklepy')->with(['success'=>'sklep usunięty pomyślnie!']);

}

        function edit($id){
             session()->forget('brands_collection');
             $store = DB::table('stores')->where('id',$id)->first();


             $brand= DB::table('brands')->get();
             $brand_collection = DB::table('brand_store')->where('store_id',$id)->get();
             return view('/stores/edit-store',compact('store','brand','brand_collection'));
        }

            function update(Request $request){



                $rules = [
                    'name' => 'required',
                    'street' => 'required',
                    'place' => 'required',
                    'latitude' => 'required',
                    'longitude' => 'required',
                    'phone' => 'required',
                    'web_page' => 'required',
                    'brands'=>'required',
                    'email' => 'required|string|email|max:255|unique:stores,email,'.$request->store,
                    'des'=>'required',
                    'logo'=>'image|mimes:jpeg,png,jpg|max:2048'



                ];

                $customMessages = [
                    'required' => 'The :attribute field is required.'
                ];

                $this->validate($request, $rules, $customMessages);

                if ($request->hasFile('logo')){
                    $imageName = time().rand(10,1000).'.'.$request->logo->extension();
                    $request->logo->move(public_path('/img'), $imageName);

                    $image=url('/public/img/'. $imageName);
                    $data=array(
                        'name'=>$request->name,
                        'street'=>$request->street,
                        'place'=>$request->place,
                        'latitude'=>$request->latitude,
                        'longitude'=>$request->longitude,
                        'phone'=>$request->phone,
                        'web_page'=>$request->web_page,
                        'apartment_no'=>$request->apartment_no,
                        'email'=>$request->email,
                        'des'=>$request->des,
                        'logo'=>$image

                    );

                    $stores=DB::table('stores')->where('id',$request->store)->update($data);

                }else{
                    $data=array(
                        'name'=>$request->name,
                        'street'=>$request->street,
                        'place'=>$request->place,
                        'latitude'=>$request->latitude,
                        'longitude'=>$request->longitude,
                        'phone'=>$request->phone,
                        'web_page'=>$request->web_page,
                        'apartment_no'=>$request->apartment_no,
                        'email'=>$request->email,
                        'des'=>$request->des,


                    );

                    $stores=DB::table('stores')->where('id',$request->store)->update($data);
                }

              DB::table('brand_store')->where('store_id',$request->store)->delete();
                foreach($request->brands as $b){


                    $brand=array(
                        'brand_id'=>$b,
                        'store_id'=>$request->store
                    );
                    $brand_store= DB::table('brand_store')->insertGetId($brand);

                }

                return redirect('/sklepy')->with(['success'=>'Sklep został zaktualizowany pomyślnie !']);

            }



}
