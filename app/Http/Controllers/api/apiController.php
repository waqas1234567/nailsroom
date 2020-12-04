<?php

namespace App\Http\Controllers\api;

use App\collections;
use App\Mail\forgetPassword;
use App\Mail\subscriptionEmail;
use App\News;
use App\Notification;
use App\premiumNew;
use App\price;
use App\Privacy;
use App\subscription;
use App\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Hash;
use Illuminate\Foundation\Auth\VerifiesEmails;
use Illuminate\Auth\Events\Verified;

class apiController extends Controller
{

    use VerifiesEmails;

    //
    function store(Request $request)
    {
        $validator = Validator::make($request->all(), [

            'email' => 'required|email|unique:users,email',
            'password' => 'required',

        ]);

        if ($validator->passes()) {
            $user = User::create([
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'api_token' => Str::random(60),
            ]);

            $user->sendApiEmailVerificationNotification();


            if ($user) {
                $response = array(
                    'meta' => array(
                        'errCode' => 200,
                        'message' => 'Rejestracja użytkownika powiodła się !'

                    ),
                    'data' => $user
                );

                return response()->json($response, 200);
            } else {
                $response = array(
                    'meta' => array(
                        'errCode' => 500,
                        'message' => ['coś poszło nie tak ! ']

                    ),
                    'data' => null

                );

                return response()->json($response, 500);
            }

        } else {
            $response = array(
                'meta' => array(
                    'errCode' => 400,
                    'message' => $validator->errors()->all()

                ),
                'data' => null

            );

            return response()->json($response, 400);

        }

    }

    function login(Request $request)
    {

        $validator = Validator::make($request->all(), [

            'email' => 'required',
            'password' => 'required',

        ]);
        if ($validator->passes()) {

            $user = DB::table('users')->where('email', '=', $request->email)->first();

            if (isset($user->id)) {

                if (empty($user->email_verified_at)) {

                    $response = array(
                        'meta' => array(
                            'errCode' => 400,
                            'message' => ['Twoje konto nie zostało aktywowane, sprawdź pocztę i aktywuj konto!']

                        ),
                        'data' => null

                    );

                    return response()->json($response, 400);


                }


                $val = Hash::check($request->password, $user->password);
            } else {

                $response = array(
                    'meta' => array(
                        'errCode' => 404,
                        'message' => ['E-mail lub hasło jest nieprawidłowe!']

                    ),
                    'data' => null

                );

                return response()->json($response, 404);
            }

            if ($val == 1) {
                $data = array(
                    'id' => $user->id,
                    'api_token' => $user->api_token,
                    'email' => $user->email

                );

                $response = array(
                    'meta' => array(
                        'errCode' => 200,
                        'message' => 'Zaloguj się pomyślnie!'

                    ),
                    'data' => $data

                );

                return response()->json($response, 200);

            } else {
                $response = array(
                    'meta' => array(
                        'errCode' => 404,
                        'message' => ['E-mail lub hasło jest nieprawidłowe!']

                    ),
                    'data' => null

                );

                return response()->json($response, 404);
            }

        } else {

            $response = array(
                'meta' => array(
                    'errCode' => 400,
                    'message' => $validator->errors()->all()

                ),
                'data' => null

            );

            return response()->json($response, 400);

        }


    }

    function forgetPassword(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'email' => 'required',
        ]);

        if ($validator->passes()) {
            $user = DB::table('users')->where('email', '=', $request->email)->first();

            if ($user) {

                //   $random = str_shuffle('abcdefghjklmnopqrstuvwxyzABCDEFGHJKLMNOPQRSTUVWXYZ234567890!$%^&!$%^&');
                //   $code = substr($random, 0, 15);

                $digits = 4;
                $code = rand(pow(10, $digits - 1), pow(10, $digits) - 1);

                Mail::to($request->email)->send(new forgetPassword($code));

                $response = array(
                    'meta' => array(
                        'errCode' => 200,
                        'message' => 'Kod resetowania hasła wysłany na e-mail !'
                    ),
                    'data' => array(
                        'code' => $code,
                        'api_token' => $user->api_token
                    )


                );

                return response()->json($response, 200);


            } else {

                $response = array(
                    'meta' => array(
                        'errCode' => 404,
                        'message' => ['Nie znaleziona nagrania ! ']

                    ),
                    'data' => null

                );

                return response()->json($response, 404);

            }

        } else {

            $response = array(
                'meta' => array(
                    'errCode' => 400,
                    'message' => $validator->errors()->all()

                ),
                'data' => null

            );

            return response()->json($response, 400);


        }


    }

    function resetPassword(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'password' => 'required',
            'api_token' => 'required'
        ]);


        if ($validator->passes()) {

            $user = DB::table('users')->where('api_token', '=', $request->api_token)->first();


            if ($user) {

                if (!empty($request->oldPassword)) {

                    $val = Hash::check($request->oldPassword, $user->password);

                    if ($val == 0) {
                        $response = array(
                            'meta' => array(
                                'errCode' => 400,
                                'message' => 'Stare hasło jest nieprawidłowe!'
                            ),
                            'data' => true

                        );
                        return response()->json($response, 400);
                    } else {

                        $password = Hash::make($request->password);
                        $check = DB::table('users')->where('id', $user->id)->update(array('password' => $password));

                        $response = array(
                            'meta' => array(
                                'errCode' => 200,
                                'message' => 'Hasło zostało zresetowane pomyślnie !'
                            ),
                            'data' => true

                        );
                        return response()->json($response, 200);
                    }


                }

                $password = Hash::make($request->password);
                $check = DB::table('users')->where('id', $user->id)->update(array('password' => $password));

                if ($check) {
                    $response = array(
                        'meta' => array(
                            'errCode' => 200,
                            'message' => 'Hasło zostało zresetowane pomyślnie !'
                        ),
                        'data' => true

                    );
                    return response()->json($response, 200);


                } else {
                    $response = array(
                        'meta' => array(
                            'errCode' => 500,
                            'message' => ['coś poszło nie tak !']
                        ),
                        'data' => true

                    );
                    return response()->json($response, 500);

                }


            } else {
                $response = array(
                    'meta' => array(
                        'errCode' => 404,
                        'message' => ['Nie znaleziona nagrania ! ']

                    ),
                    'data' => null

                );

                return response()->json($response, 404);

            }


        } else {
            $response = array(
                'meta' => array(
                    'errCode' => 400,
                    'message' => $validator->errors()->all()

                ),
                'data' => null

            );

            return response()->json($response, 400);
        }


    }

    function getNews($category)
    {
        $news = DB::table('news')->select('news.id', 'news.title', 'news.contents', 'news.image', 'news.video')
            ->join('news_categories', 'news.newscategory_id', '=', 'news_categories.id')->
            where('news_categories.name', $category)->orderBy('id', 'desc')->get();

        $news_array = array();

        foreach ($news as $n) {


            $notification = Notification::where('news_id', $n->id)->first();
            $notification_id = 0;
            if (isset($notification)) {
                $notification_id = $notification->id;
            }

            $data = array(
                'id' => $n->id,
                'title' => $n->title,
                'contents' => $n->contents,
                'image' => $n->image,
                'notification_id' => $notification_id,
                'video' => $n->video

            );

            array_push($news_array, $data);


        }

        if (isset($news)) {
            $response = array(
                'meta' => array(
                    'errCode' => 200,
                    'message' => 'News data!'
                ),
                'data' => $news_array

            );
            return response()->json($response, 200);
        } else {
            $response = array(
                'meta' => array(
                    'errCode' => 404,
                    'message' => ["
Nie znaleziono żadnych wiadomości!"]

                ),
                'data' => null

            );

            return response()->json($response, 404);

        }
    }

    function getBrands()
    {
        $brands = DB::table('brands')->get();

        $brand = array();
        if (isset($brands)) {
            foreach ($brands as $b) {
                $collection = array();
                $collections = DB::table('collections')->where('brand_id', $b->id)->get();

                foreach ($collections as $c) {
                    $colour = DB::table('colors')->where('collections_id', $c->id)->get();
                    $data = array(
                        'collection_id' => $c->id,
                        'collection_name' => $c->name,
                        'colors' => $colour

                    );
                    array_push($collection, $data);
                }
                $brand_data = array(
                    'brand_id' => $b->id,
                    'brand_name' => $b->name,
                    'collection' => $collection

                );
                array_push($brand, $brand_data);

            }
            $response = array(
                'meta' => array(
                    'errCode' => 200,
                    'message' => 'Brands data!'
                ),
                'data' => $brand

            );
            return response()->json($response, 200);
        } else {
            $response = array(
                'meta' => array(
                    'errCode' => 404,
                    'message' => ["No record found!"]

                ),
                'data' => null

            );

            return response()->json($response, 404);

        }


    }


    function getStores()
    {
        $stores = DB::table('stores')->get();

        $stores_array = array();

        if (isset($stores)) {
            foreach ($stores as $s) {
                $brand = DB::table('brand_store')->where('store_id', $s->id)
                    ->join('brands', 'brand_store.brand_id', '=', 'brands.id')->where('store_id', $s->id)
                    ->get();
                $brand_array = array();
                foreach ($brand as $b) {
                    $collection_array = array();
                    $collections = collections::where('brand_id', $b->id)->get();
                    $col = DB::table('brand_store_collections')->where('store_id', $s->id)->get();
                    foreach ($collections as $c) {
                        $check = 0;
                        foreach ($col as $c2) {

                            if ($c->id == $c2->collection_id) {
                                $check = 1;
                            }
                        }

                        if ($check == 1) {
                            $collection_name = DB::table('collections')->where('id', $c->id)->first();

                            $collection_data = array(
                                'collection_id' => $c->id,
                                'collection_name' => $collection_name->name
                            );

                            array_push($collection_array, $collection_data);
                        }
                    }

                    $brand_data = array(
                        'brand_id' => $b->id,
                        'brand_name' => $b->name,
                        'brand_image' => $b->brand_image,
                        'collections' => $collection_array
                    );
                    array_push($brand_array, $brand_data);

                }

                $store_data = array(
                    'store_id' => $s->id,
                    'store_name' => $s->name,
                    'latitude' => $s->latitude,
                    'longitude' => $s->longitude,
                    'email' => $s->email,
                    'web_page' => $s->web_page,
                    'street' => $s->street,
                    'place' => $s->place,
                    'phone' => $s->phone,
                    'des' => $s->des,
                    'logo' => $s->logo,
                    'brand' => $brand_array,

                );

                array_push($stores_array, $store_data);


            }
            $response = array(
                'meta' => array(
                    'errCode' => 200,
                    'message' => 'News data!'
                ),
                'data' => $brand

            );
            return response()->json($stores_array, 200);

        } else {
            $response = array(
                'meta' => array(
                    'errCode' => 404,
                    'message' => ["No Notification found!"]

                ),
                'data' => null

            );

            return response()->json($response, 404);
        }
    }

    function getNotifications(Request $request)
    {
        $notifications = Notification::orderBy('id', 'desc')->get();
        $notification_array = array();


        foreach ($notifications as $n) {


            if ($n->category == 'notification') {
                $read = 0;

                $check = DB::table('readNotifications')->where([['user_id', $request->user_id], ['notification_id', $n->id]])->count();
                if ($check > 0) {
                    $read = 1;
                }


                $data = array(
                    'id' => $n->id,
                    'title' => $n->title,
                    'contents' => $n->content,
                    'images' => $n->image,
                    'read_status' => $read
                );

                array_push($notification_array, $data);


            } else {

                $read = 0;

                $check = DB::table('readNotifications')->where([['user_id', $request->user_id], ['notification_id', $n->id]])->count();
                if ($check > 0) {
                    $read = 1;
                }

                $news = DB::table('news')->where('id', $n->news_id)->first();

                if (isset($news)) {
                    $data = array(
                        'id' => $n->id,
                        'title' => $news->title,
                        'contents' => $news->contents,
                        'images' => $news->image,
                        'read_status' => $read
                    );

                    array_push($notification_array, $data);
                }


            }

        }


        if (isset($notifications)) {
            $response = array(
                'meta' => array(
                    'errCode' => 200,
                    'message' => 'Notifications!'
                ),
                'data' => $notification_array

            );
            return response()->json($response, 200);

        } else {
            $response = array(
                'meta' => array(
                    'errCode' => 404,
                    'message' => ["No Notification found!"]

                ),
                'data' => null

            );

            return response()->json($response, 404);
        }
    }


    function resetEmail(Request $request)
    {
        $validator = Validator::make($request->all(), [

            'oldEmail' => 'required',
            'newEmail' => 'required|email|unique:users,email',

        ]);
        if ($validator->passes()) {
            $user = User::where('email', $request->oldEmail)->first();

            if (isset($user->email) && !empty($user->email)) {

                $data = array(
                    'email' => $request->newEmail
                );
                $newUser = User::where('id', $user->id)->update($data);
                if ($newUser == 1) {
                    $response = array(
                        'meta' => array(
                            'errCode' => 200,
                            'message' => 'Zresetuj e-mail pomyślnie!'
                        ),
                        'data' => $newUser

                    );
                    return response()->json($response, 200);
                } else {
                    $response = array(
                        'meta' => array(
                            'errCode' => 500,
                            'message' => 'Coś poszło nie tak!'
                        ),
                        'data' => $newUser

                    );
                    return response()->json($response, 500);
                }


            } else {
                $response = array(
                    'meta' => array(
                        'errCode' => 404,
                        'message' => 'Nie znaleziona nagrania!'
                    ),
                    'data' => null

                );
                return response()->json($response, 404);
            }
        } else {
            $response = array(
                'meta' => array(
                    'errCode' => 400,
                    'message' => $validator->errors()->all()

                ),
                'data' => null

            );

            return response()->json($response, 400);
        }


    }


    function privacy()
    {
        $privacy = Privacy::where('id', 1)->first();

        if (isset($privacy->id)) {
            $response = array(
                'meta' => array(
                    'errCode' => 200,
                    'message' => 'Privacy and Regulations!'
                ),
                'data' => $privacy

            );
            return response()->json($response, 200);
        } else {
            $response = array(
                'meta' => array(
                    'errCode' => 500,
                    'message' => 'Something went wrong!'
                ),
                'data' => null

            );
            return response()->json($response, 500);
        }


    }


    function readNotification(Request $request)
    {

        $validator = Validator::make($request->all(), [

            'notification_id' => 'required',
            'user_id' => 'required',

        ]);

        if ($validator->passes()) {
            $data = array(
                'notification_id' => $request->notification_id,
                'user_id' => $request->user_id
            );
            $check = DB::table('readNotifications')->where([['user_id', $request->user_id], ['notification_id', $request->notification_id]])->count();
            if ($check == 0) {
                DB::table('readNotifications')->insert($data);

            }
            $response = array(
                'meta' => array(
                    'errCode' => 200,
                    'message' => '
Status powiadomienia zaktualizowany!'
                ),
                'data' => null

            );
            return response()->json($response, 200);


        } else {
            $response = array(
                'meta' => array(
                    'errCode' => 400,
                    'message' => $validator->errors()->all()

                ),
                'data' => null

            );

            return response()->json($response, 400);
        }


    }


    function getPremiumNews()
    {

        $premiumNews = premiumNew::get();


        $response = array(
            'meta' => array(
                'errCode' => 200,
                'message' => 'News data'
            ),
            'data' => $premiumNews

        );
        return response()->json($response, 200);

    }


    function getSubscriptionPrice()
    {
        $price = price::all();
        $response = array(
            'meta' => array(
                'errCode' => 200,
                'message' => 'News data'
            ),
            'data' => $price

        );
        return response()->json($response, 200);
    }


    function addSubscriptionData(request $Request)
    {

        dd($Request->all());

        $subscription = subscription::create($Request->all());

        if (isset($subscription->id)) {
            $response = array(
                'meta' => array(
                    'errCode' => 200,
                    'message' => 'Subscription data added successfully!'
                ),
                'data' => $subscription

            );
            return response()->json($response, 200);
        } else {
            $response = array(
                'meta' => array(
                    'errCode' => 500,
                    'message' => 'Oops something went wrong !'
                ),
                'data' => null
            );
            return response()->json($response, 500);
        }


    }


    function getPaymentMethods(request $request)
    {

        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => "https://sandbox.przelewy24.pl/api/v1/card/info/305862899",
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "GET",

            CURLOPT_HTTPHEADER => array(
                "Authorization: Basic OTA3MTMgIDpmNjgxYzVmYzhjN2M2ZDI0MGI2OWNkZDM3ZGM1ZDk3Mg==",
                "Content-Type: application/json",
                "Postman-Token: cec960ea-488f-42d1-b2db-5ffb64443dda",
                "cache-control: no-cache"
            ),
        ));

        $res = curl_exec($curl);
        $err = curl_error($curl);

        curl_close($curl);

        if ($err) {
            $response = array(
                'meta' => array(
                    'errCode' => 500,
                    'message' => 'issues with api !'
                ),
                'data' => $err
            );
            return response()->json($response, 500);
        } else {
            $response = array(
                'meta' => array(
                    'errCode' => 200,
                    'message' => 'Payment method data !'
                ),
                'data' => json_decode($res)
            );
            return response()->json($response, 200);
        }

    }


    function registerPayment(request $request)
    {

        $price = db::table('prices')->first();

        $user=User::where('id',$request->user_id)->first();



        $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $charactersLength = strlen($characters);
        $randomString = '';
        for ($i = 0; $i < 20; $i++) {
            $randomString .= $characters[rand(0, $charactersLength - 1)];
        }

        $data=array(
            'sessionId'=>$randomString,
            'merchantId'=>$price->merchant,
            'amount'=>$price->price,
            'currency'=>$price->currency,
            'crc'=>$price->crc
        );

        $sign=hash('sha384',json_encode($data,JSON_UNESCAPED_UNICODE|JSON_UNESCAPED_SLASHES));

        $curl = curl_init();
        $data=array(
            "merchantId"=>$price->merchant,
            "posId"=>$price->merchant,
            "sessionId"=>$randomString,
            "amount"=>$price->price,
            "currency"=>$price->currency,
            "description"=>"Payment for nailsroom premium",
            "email"=>$user->email,
            "country"=>$price->country,
            "language"=>$price->language,
//            "methodRefId"=>"8D460D2E-621ACA6C-47F1847C-978E0BE1",
//  "method"=>25,
            "urlReturn"=>"https://www.google.com/",
            "urlStatus"=>"http://panel.nailsroom.com.pl/checkStatus/",
            "sign"=>$sign,
//            "timeLimit"=>99,
//            "channel"=>1,
            "cart"=>[array(
                "sellerId"=>"test50",
                "price"=>$price->price,
                "quantity"=>1,
                "description"=>"nailsroom premium news",
                "number"=>"1",
                "name"=>"nailsroom premium",
                "sellerCategory"=>"app"
            )]
        );


        curl_setopt_array($curl, array(
            CURLOPT_URL => "https://sandbox.przelewy24.pl/api/v1/transaction/register",
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "POST",
            CURLOPT_POSTFIELDS=>json_encode($data),

            CURLOPT_HTTPHEADER => array(
                "Authorization: Basic OTA3MTMgIDpmNjgxYzVmYzhjN2M2ZDI0MGI2OWNkZDM3ZGM1ZDk3Mg==",
                "Content-Type: application/json",
                "Postman-Token: cec960ea-488f-42d1-b2db-5ffb64443dda",
                "cache-control: no-cache"
            ),
        ));

        $response = curl_exec($curl);
        $err = curl_error($curl);

        curl_close($curl);

        if ($err) {
            echo "cURL Error #:" . $err;
        } else {




            $data=json_decode($response);

            if(isset($data->data->token)){


                $card=array(
                    "transactionToken"=> $data->data->token,
                    "cardNumber"=> $request->card_no,
                    "cardDate"=> $request->date,
                    "cvv"=> $request->cvv,
                    "clientName"=> $request->name

                );


                $curl = curl_init();

                curl_setopt_array($curl, array(
                    CURLOPT_URL => "https://sandbox.przelewy24.pl/api/v1/card/pay",
                    CURLOPT_RETURNTRANSFER => true,
                    CURLOPT_ENCODING => "",
                    CURLOPT_MAXREDIRS => 10,
                    CURLOPT_TIMEOUT => 30,
                    CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                    CURLOPT_CUSTOMREQUEST => "POST",
                    CURLOPT_POSTFIELDS=>json_encode($card),

                    CURLOPT_HTTPHEADER => array(
                        "Authorization: Basic OTA3MTMgIDpmNjgxYzVmYzhjN2M2ZDI0MGI2OWNkZDM3ZGM1ZDk3Mg==",
                        "Content-Type: application/json",
                        "Postman-Token: cec960ea-488f-42d1-b2db-5ffb64443dda",
                        "cache-control: no-cache"
                    ),
                ));


                $response = curl_exec($curl);





                $err = curl_error($curl);

                curl_close($curl);

                if ($err) {
                    echo "cURL Error #:" . $err;
                }else{

                    $cardResponse=json_decode($response);

                    if(isset($cardResponse->data->orderId)){


                        $mytime = Carbon::now();

                        $subscription=subscription::where('user_id',$request->user_id)->first();

                       $cardInfo= db::table('cardInformation')->where('user_id',$request->user_id)->first();

                       if($cardInfo){

                           $card=array(
                               "card_no"=> $request->card_no,
                               "date"=> $request->date,
                               "cvv"=> $request->cvv,

                           );

                             db::table('cardInformation')->where('id',$cardInfo->id)->update($card);


                       }else{

                           $card=array(
                               "card_no"=> $request->card_no,
                               "date"=> $request->date,
                               "cvv"=> $request->cvv,
                               "name"=> $request->name,
                               'user_id'=>$request->user_id
                           );

                           db::table('cardInformation')->insert($card);

                       }


                        if($subscription){
                            $transactionData=array(
                                'user_id'=>$request->user_id,
                                'payment_date'=>$mytime->toDateTimeString(),
                                'price'=>$price->price,
                                'currency'=>$price->currency,
                                'is_premium'=>0,
                                'session_id'=>$randomString,
                                'order_id'=>$cardResponse->data->orderId,
                                'status'=>'unpaid'

                            );

                            subscription::where('id', $subscription->id)->update($transactionData);

                        }else{

                            $transactionData=array(
                                'user_id'=>$request->user_id,
                                'payment_date'=>$mytime->toDateTimeString(),
                                'price'=>$price->price,
                                'currency'=>$price->currency,
                                'is_premium'=>0,
                                'session_id'=>$randomString,
                                'order_id'=>$cardResponse->data->orderId
                            );

                            subscription::create($transactionData);

                        }


                        $response = array(
                            'meta' => array(
                                'errCode' => 200,
                                'message' => 'payment proceed successfully (Waiting for approval)!'
                            ),
                            'data' => $cardResponse->data

                        );
                        return response()->json($response, 200);
                    }else{
                        $response = array(
                            'meta' => array(
                                'errCode' => 400,
                                'message' => [$cardResponse->error]

                            ),
                            'data' => ''

                        );

                        return response()->json($response, 400);
                    }



                }



            }else{

                $response = array(
                    'meta' => array(
                        'errCode' => 400,
                        'message' => [$data->error]

                    ),
                    'data' => ''

                );

                return response()->json($response, 400);


            }








        }

    }


    function subscriptionStatus(Request $request){


           $subscription=subscription::where('user_id',$request->user_id)->first();

           if($subscription){
               $response = array(
                   'meta' => array(
                       'errCode' => 200,
                       'message' =>'subscription information !'

                   ),
                   'data' =>$subscription

               );

               return response()->json($response, 200);
           }else{
               $response = array(
                   'meta' => array(
                       'errCode' => 404,
                       'message' =>['No data found!']

                   ),
                   'data' =>''

               );

               return response()->json($response, 404);
           }



    }


    function cancelSubscription(request $request){


        $response=subscription::where('user_id',$request->user_id)->update(['is_cancel'=>1]);

        if($response==1){
            $response = array(
                'meta' => array(
                    'errCode' => 200,
                    'message' =>'subscription canceled successfully !'

                ),
                'data' =>''

            );

            return response()->json($response, 200);
        }else{
            $response = array(
                'meta' => array(
                    'errCode' => 500,
                    'message' =>['Something went wrong!']
                ),
                'data' =>''

            );

            return response()->json($response, 500);
        }

    }



    function checkStatus(request $request){

        $mytime = Carbon::now();
        $transactionData = array(
            'user_id' => $request->id,
            'payment_date' => $mytime->toDateTimeString(),
            'price' => $request->p24_amount/100,
            'currency' => $request->p24_currency,
            'is_premium' => 0,
            'session_id' => $request->p24_session_id,
            'order_id' => $request->p24_order_id,
            'statement'=>$request->p24_statement,
            'method'=>$request->p24_method,
            'sign'=>$request->p24_sign,
            'status'=>'paid',
            'is_premium'=>1,
            'is_cancel'=>0


        );

        $subscription = subscription::where('user_id', $request->id)->first();

        if($subscription){
            subscription::where('id', $subscription->id)->update($transactionData);


        }else{
            subscription::create($transactionData);

        }


        $data =   db::table('prices')->where('id',1)->first();

        $datas=array(
            'sessionId'=>$request->p24_session_id,
            "orderId"=>(int)$request->p24_order_id,
            "amount"=>(int)$request->p24_amount,
            "currency"=>$request->p24_currency,
            "crc"=>$data->crc
        );


      $sign = hash('sha384',json_encode($datas,JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES));

        $verify = array(
            "merchantId" => $data->merchant,
            "posId" => $data->merchant,
            "sessionId" => $request->p24_session_id,
            "amount" => (int)$request->p24_amount,
            "currency" => $request->p24_currency,
            "orderId" => (int)$request->p24_order_id,
            "sign" => $sign
        );

        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => "https://sandbox.przelewy24.pl/api/v1/transaction/verify",
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "PUT",
            CURLOPT_POSTFIELDS => json_encode($verify),

            CURLOPT_HTTPHEADER => array(
                "Authorization: Basic OTA3MTMgIDpmNjgxYzVmYzhjN2M2ZDI0MGI2OWNkZDM3ZGM1ZDk3Mg==",
                "Content-Type: application/json",
                "Postman-Token: cec960ea-488f-42d1-b2db-5ffb64443dda",
                "cache-control: no-cache"
            ),
        ));
        $response = curl_exec($curl);
        $err = curl_error($curl);

        curl_close($curl);


        if ($err) {
            echo "cURL Error #:" . $err;

//            db::table('test_table')->insert(['status'=>json_encode($err)]);
        } else {

            $user = User::where('id',$request->id)->first();

            Mail::to($user->email)->send(new subscriptionEmail());




//            db::table('test_table')->insert(['status'=>$response]);
        }








        return response()->json(json_encode('success'),200);
    }



    function verifyPayment($orderId,$sessionId){
//        $subscriptions = subscription::where([['is_premium', 0], ['status' , 'unpaid'],['is_cancel',0]])->get();


           return 1;



    }


    function getPatterns(Request $request){





            $patternData=db::table('pattern')->where('brand_id',$request->brand_id)
                ->get();



         $response = array(
            'meta' => array(
                'errCode' => 200,
                'message' =>'pattern data !'

            ),
            'data' => $patternData

        );

        return response()->json($response, 200);


    }

}


