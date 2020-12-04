<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class brand extends Model
{
    protected $fillable = [
        'name','brand_image','user_id'
    ];


    public function collections()
    {
        return $this->hasMany('App\collections');
    }

    public function store()
    {
        return $this->belongsToMany('App\store');
    }


}
