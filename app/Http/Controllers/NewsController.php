<?php

namespace App\Http\Controllers;

use App\News;
use mysql_xdevapi\Exception;
use Vimeo\Laravel\Facades\Vimeo;
use App\Notification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use LaravelFCM\Message\OptionsBuilder;
use LaravelFCM\Message\PayloadDataBuilder;
use LaravelFCM\Message\PayloadNotificationBuilder;
use FCM;
use LaravelFCM\Message\Topics;
use Yajra\DataTables\Facades\DataTables;

class NewsController extends Controller
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


        $role=Auth::user()->role;
        return  view('newsy/Newsy-Aktualnosci',compact('role'));

    }


    function ajaxnewsy(Request $request){

        if(Auth::user()->role=='Super Admin') {
            $news = News::with('NewsCategory')->orderBy('id', 'desc')->get();
            return Datatables::of($news)
                ->addIndexColumn()
                ->addColumn('date', function($row){
                     return date('d-m-Y', strtotime($row->created_at));
                })
                ->addColumn('category', function($row){

                    return $row->newscategory->name;
                })
                ->addColumn('action', function($row){

                    $btn = ' <a style="margin-right:1rem" href="/editNewsy/'.$row->id.'"><img src="/public/assets/img/Vector.png"></a>
                    <a onclick="deleteNews('.$row->id.')" href="#"><img src="/public/assets/img/delete.png"></a>';

                    return $btn;
                })
                ->rawColumns(['category'])
                ->rawColumns(['date'])
                ->rawColumns(['action'])
                ->make(true);
        }else{
            $news = News::with('NewsCategory')->where('user_id',Auth::user()->id)->orderBy('id', 'desc')->get();
            return Datatables::of($news)
                ->addIndexColumn()
                ->addColumn('date', function($row){
                    return date('d-m-Y', strtotime($row->created_at));
                })
                ->addColumn('category', function($row){

                    return $row->newscategory->name;
                })

                ->addColumn('action', function($row){

                    $btn = ' <a style="margin-right:1rem" href="/editNewsy/'.$row->id.'"><img src="/public/assets/img/Vector.png"></a>
                    <a onclick="deleteNews('.$row->id.')" href="#"><img src="/public/assets/img/delete.png"></a>';

                    return $btn;
                })
                ->rawColumns(['category'])
                ->rawColumns(['date'])
                ->rawColumns(['action'])
                ->make(true);
        }
        return  view('newsy/Newsy-Aktualnosci');

    }



    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('newsy/newsy');
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
            'title' => 'required',
            'contents' => 'required',
            'category' => 'required',
            'image' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);

        if ($request->hasFile('image')){
            $imageName = time().rand(10,1000).'.'.$request->image->extension();
            $request->image->move(public_path('/img'), $imageName);

            $image=url('/public/img/'. $imageName);
        }
        $video='';

        if ($request->hasFile('video')){
            try {
                $video = Vimeo::upload($request->video,[
                    'name' => $request->title
                  ]);


            } catch (Throwable $e) {

            }

//            Vimeo::uploadImage($video.'/pictures', public_path('/img/'.$imageName), true);


        }

        $user_id=0;

        if(Auth::user()->role=='Admin'){
             $user_id=Auth::user()->id;
        }

        $data=array(
            'title'=>$request->title,
            'contents'=>$request->contents,
            'newscategory_id'=>$request->category,
            'image'=>$image,
            'user_id'=>$user_id,
            'video'=>$video

        );

        $news=News::create($data);

        $data=array(
            'user_id'=>$user_id,
            'news_id'=>$news->id,
            'category'=>'news',
        );

        $news=Notification::create($data);
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
        //ios push notification
        $notificationBuilderios = new PayloadNotificationBuilder($stringtitle);
        $notificationBuilderios->setBody($string)

            ->setSound('default');


        $notificationBuilderios->setBodyLocationKey('news');
        $notificationios = $notificationBuilderios->build();
        $topicios = new Topics();
        $topicios->topic('notification');

        $topicResponseios = FCM::sendToTopic($topicios, null, $notificationios, null);

        $topicResponseios->isSuccess();
        $topicResponseios->shouldRetry();
        $topicResponseios->error();

        //android push notification
        if($request->category==3){
            $messageResponse=array(
                'category'=>'Premium',
                'body'=>$string,
                'title'=>$stringtitle
            );
        }else{

            $messageResponse=array(
                'category'=>'news',
                'body'=>$string,
                'title'=>$request->title
            );
        }


        $iosResponse=array(
               'aps'=>array(
                 'alert'=>array(
                     'body'=>'test',
                     'title'=>'News'
                 ),
                'sound' => 'default'
        )
        );

            $message=json_encode($messageResponse);




            $dataBuilder = new PayloadDataBuilder();

        $dataBuilder->addData(array(
            'noti'=> $message,

        ));



        $data = $dataBuilder->build();
        $topic = new Topics();
        $topic->topic('news');
        $topicResponse = FCM::sendToTopic($topic, null, null, $data);
        $topicResponse->isSuccess();
        $topicResponse->shouldRetry();
        $topicResponse->error();
        return redirect('/newsy')->with(['success'=>'
wiadomości zostały pomyślnie dodane !']);

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
        $news = News::find($id);
        return view('newsy/newsy-edit',compact('news'));

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
            'title' => 'required',
            'contents' => 'required',
            'category' => 'required',
            'image' => 'image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);

        $news=News::where('id',$request->news_id)->first();
        if ($request->hasFile('image')){
            $imageName = time().rand(10,1000).'.'.$request->image->extension();
            $request->image->move(public_path('/img'), $imageName);

            $image=url('/public/img/'. $imageName);
            $data=array(
                'title'=>$request->title,
                'contents'=>$request->contents,
                'newscategory_id'=>$request->category,
                'image'=>$image

            );

            News::where('id',$request->news_id)->update($data);

        }else{
            $data=array(
                'title'=>$request->title,
                'contents'=>$request->contents,
                'newscategory_id'=>$request->category,


            );

            News::where('id',$request->news_id)->update($data);

        }


        if ($request->hasFile('video')){
            try {

                if(!empty($news->video)){
                    Vimeo::request($news->video, array(), 'DELETE');


                    $video = Vimeo::upload($request->video,[
                        'name' => $request->title
                    ]);
                    $updatedData=array(
                        'video'=> $video
                    );

                    News::where('id',$request->news_id)->update($updatedData);

                }
            } catch (Throwable $e) {

            }


        }




        return redirect('/newsy')->with(['success'=>'
wiadomości zostały pomyślnie zaktualizowane !']);



    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
          $news=News::where('id',$id)->first();

          News::where('id',$id)->delete();
          Notification::where('news_id',$id)->delete();
          if(!empty($news->video)){
              Vimeo::request($news->video, array(), 'DELETE');
          }

        return redirect('/newsy')->with(['success'=>'
    wiadomości zostały pomyślnie usunięte!']);

    }
}
