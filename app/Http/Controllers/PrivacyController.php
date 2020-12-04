<?php

namespace App\Http\Controllers;

use App\Privacy;
use Illuminate\Http\Request;

class PrivacyController extends Controller
{
    function create(){
          $privacy=Privacy::where('id',1)->first();
        return view('Privacy/create',compact('privacy'));

    }


    function store(request $request){
        $this->validate($request, [
            'privacy' => 'required',
            'regulations'=>'required'
        ]);

        $input=$request->all('privacy','regulations');

        Privacy::where('id',1)->update($input);

        return redirect()->back()->with(['success'=>'Prywatność i Regulamin zostały zapisane pomyślnie !']);
    }
}
