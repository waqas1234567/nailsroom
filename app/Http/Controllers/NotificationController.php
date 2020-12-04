<?php

namespace App\Http\Controllers;

use App\Notification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use LaravelFCM\Message\OptionsBuilder;
use LaravelFCM\Message\PayloadDataBuilder;
use LaravelFCM\Message\PayloadNotificationBuilder;
use FCM;
use LaravelFCM\Message\Topics;
use Yajra\DataTables\Facades\DataTables;

class NotificationController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        if(Auth::user()->role=='Super Admin') {
            $notifications = Notification::where('category','notification')->get();
        }else{
            $notifications = Notification::where([['user_id',Auth::user()->id],['category','notification']])->get();

        }

        $role=Auth::user()->role;

        if($notifications){
            return view('Notifications/index',compact('notifications','role'));

        }else{
            abort(404);

        }

    }

    public function ajaxnotification(){
        if(Auth::user()->role=='Super Admin') {
            $notifications = Notification::where('category','notification')->get();
            return Datatables::of($notifications)
                ->addIndexColumn()
                ->addColumn('date', function($row){
                    return date('d-m-Y', strtotime($row->created_at));
                })
                ->addColumn('action', function($row){
                    $btn = '<a style="margin-right:1rem" href="/powiadomienia/edytowac-powiadomienia/'.$row->id.'"><img src="/public/assets/img/Vector.png"></a>
                            <a onclick="deleteNotification('.$row->id.')" href="#"><img src="/public/assets/img/delete.png"></a>';

                    return $btn;
                })
                ->rawColumns(['date'])
                ->rawColumns(['action'])
                ->make(true);
        }else{
            $notifications = Notification::where([['user_id',Auth::user()->id],['category','notification']])->get();
            return Datatables::of($notifications)
                ->addIndexColumn()
                ->addColumn('date', function($row){
                    return date('d-m-Y', strtotime($row->created_at));
                })
                ->addColumn('action', function($row){
                    $btn = '<a style="margin-right:1rem" href="/powiadomienia/edytowac-powiadomienia/'.$row->id.'"><img src="/public/assets/img/Vector.png"></a>
                            <a onclick="deleteNotification('.$row->id.')" href="#"><img src="/public/assets/img/delete.png"></a>';

                    return $btn;
                })
                ->rawColumns(['date'])
                ->rawColumns(['action'])
                ->make(true);

        }
        return view('Notifications/index');

    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
          return view('Notifications/create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $rules = [
            'title' => 'required',
            'contents' => 'required',
            'image' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',

        ];

        $customMessages = [
            'required' => 'The :attribute field is required.'
        ];

        $this->validate($request, $rules, $customMessages);

        if ($request->hasFile('image')){
            $imageName = time().rand(10,1000).'.'.$request->image->extension();
            $request->image->move(public_path('/img'), $imageName);

            $image=url('/public/img/'. $imageName);

        }

        $input['title']=$request->title;
        $input['content']=$request->contents;
        $input['image']=$image;

        $user_id=0;

        if(Auth::user()->role=='Admin'){
            $user_id=Auth::user()->id;
        }
        $input['user_id']=$user_id;
         $not=Notification::create($input);

        $stringtitle = strip_tags($request->title);
        if (strlen($stringtitle) > 100) {

            // truncate string
            $stringCut = substr($stringtitle, 0, 100);
            $endPoint = strrpos($stringCut, ' ');

            //if the string doesn't contain any space then it will cut without word basis.
            $stringtitle = $endPoint? substr($stringCut, 0, $endPoint) : substr($stringCut, 0);
            $stringtitle .= '... ';
        }

        $string = strip_tags($request->contents);
        if (strlen($string) > 100) {

            // truncate string
            $stringCut = substr($string, 0, 100);
            $endPoint = strrpos($stringCut, ' ');

            //if the string doesn't contain any space then it will cut without word basis.
            $string = $endPoint? substr($stringCut, 0, $endPoint) : substr($stringCut, 0);
            $string .= '... ';
        }
        try{
        $notificationBuilderios = new PayloadNotificationBuilder($stringtitle);
        $notificationBuilderios->setBody($string)

            ->setSound('default');


        $notificationBuilderios->setBodyLocationKey('notification');
        $notificationios = $notificationBuilderios->build();


        $topicios = new Topics();
        $topicios->topic('notification');

            $topicResponseios = FCM::sendToTopic($topicios, null, $notificationios, null);

        }

        catch(Exception $e) {

            dd($e);

//            return redirect('/powiadomienia/dodaj-powiadomienia')->with(['error'=>$e->getMessage()]);
        }

        $topicResponseios->isSuccess();
        $topicResponseios->shouldRetry();
        $topicResponseios->error();

        $messageResponse=array(
            'category'=>'notification',
            'body'=>$string,
            'title'=>$stringtitle
        );

           $message=json_encode($messageResponse);

        $dataBuilder = new PayloadDataBuilder();
        $dataBuilder->addData(array("noti"=>$message));

        $data = $dataBuilder->build();
        $topic = new Topics();
        $topic->topic('news');
        try {
            $topicResponse = FCM::sendToTopic($topic, null, null, $data);

        }

//catch exception
        catch(Exception $e) {

            dd($e);

//            return redirect('/powiadomienia/dodaj-powiadomienia')->with(['error'=>$e->getMessage()]);
        }
        return redirect('/powiadomienia')->with(['success'=>'Powiadomienie zostało dodane pomyślnie !']);


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

        $notification = Notification::where('id',$id)->first();
        return view('Notifications/edit',compact('notification'));

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
        $rules = [
            'title' => 'required',
            'contents' => 'required',
            'image' => 'image|mimes:jpeg,png,jpg,gif,svg|max:2048',

        ];

        $customMessages = [
            'required' => 'The :attribute field is required.'
        ];

        $this->validate($request, $rules, $customMessages);

        if ($request->hasFile('image')){

            $notification=Notification::where('id',$request->id)->first();
            $image=explode("/",$notification->image);
            if(file_exists(public_path('/img/'.$image[5]))){

                unlink(public_path('/img/'.$image[5]));

            }

            $imageName = time().rand(10,1000).'.'.$request->image->extension();
            $request->image->move(public_path('/img'), $imageName);

            $image=url('/public/img/'. $imageName);
            $input['title']=$request->title;
            $input['content']=$request->contents;
            $input['image']=$image;

            Notification::where('id',$request->id)->update($input);

        }else{
            $input['title']=$request->title;
            $input['content']=$request->contents;
            Notification::where('id',$request->id)->update($input);
        }


        return redirect('/powiadomienia')->with(['success'=>'Powiadomienie zaktualizowane pomyślnie !']);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {


          DB::table('readNotifications')->where('notification_id',$id)->delete();
          Notification::where('id',$id)->delete();
          return redirect()->back()->with(['success'=>'
Powiadomienie zostało pomyślnie usunięte !']);
    }
}
