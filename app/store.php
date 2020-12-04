<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class store extends Model
{
    public function brand()
    {
        return $this->belongsToMany('App\brand');
    }
}
