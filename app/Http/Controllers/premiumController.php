<?php

namespace App\Http\Controllers;

use App\News;
use App\premiumNew;
use App\price;
use App\subscription;
use App\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\Facades\DataTables;

class premiumController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {

         $price=price::first();

        $country = db::table('country')->get();

        return view('Premium/index',compact('price','country'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->validate($request, [
            'merchant' => 'required',
            'crc' => 'required',
            'price' => 'required',
            'currency' => 'required',
            'country' => 'required',
            'description' => 'required',
            'subscription_detail' => 'required',

        ]);

        db::table('prices')->where('id',1)->update([
            'merchant' => $request->merchant,
            'crc' =>  $request->crc,
            'price' =>  $request->price,
            'currency' =>  $request->currency,
            'country' =>  $request->country,
            'subscription_detail' => $request->subscription_detail,

        ]);

        return redirect('/Premium')->with(['success'=>'Ustawienia premium zostały zaktualizowane pomyślnie !']);

    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }


    function checkSubscriptionStatus()
    {

        $subscriptions = subscription::where([['is_premium', 1], ['status' , 'paid']])->get();
        foreach ($subscriptions as $s) {



            $subDate = $s->payment_date;
            $date = explode(" ", $subDate);
            $time = strtotime($date[0]);
            $final = date("Y-m-d", strtotime("+1 month", $time));
            $date1 = date_create(date('Y-m-d'));
            $date2 = date_create($final);
            $diff = date_diff($date1, $date2);


            if ($diff->days > 0 && $diff->invert == 1 && $s->is_cancel==0) {

                subscription::where('id', $s->id)->update(['is_premium'=>0,'status'=>'unpaid']);



            }elseif($diff->days > 0 && $diff->invert == 1 && $s->is_cancel==1){

                subscription::where('id', $s->id)->update(['is_premium'=>0,'status'=>'unpaid']);


            }else{
                echo 'remain subscribed';
            }
        }
    }


    function paymentstatus(request $request)
    {

        $body = file_get_contents("php://input");
        $webhook = json_decode($body, true);

        $myfile = fopen("test.txt", "w") or die("Unable to open file!");
        $txt = $webhook;
        fwrite($myfile, $request->all());

        fclose($myfile);


    }





    function checkStatus(request $request){

          db::table('test_table')->insert(['status'=>1231]);


          return response()->json(json_encode('success'),200);
    }


    function generateSign(){

        $data=array(
            'sessionId'=>"73d452aa-7c89-4213-812e-e628b8a9c701",
            "orderId"=>305868972,
            "amount"=>100000,
            "currency"=>"PLN",
            "crc"=>"7471a6626d39f3e2"
        );


        echo hash('sha384',json_encode($data,JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES));


    }



}
