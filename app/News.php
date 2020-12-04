<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class News extends Model
{

    protected $fillable = [
        'title', 'contents','image','newscategory_id','user_id','video'
    ];


    public function newscategory()
    {
        return $this->belongsTo('App\NewsCategory');
    }
}
