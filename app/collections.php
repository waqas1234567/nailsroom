<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class collections extends Model
{
    protected $fillable = [
        'name','brand_id'
    ];
    public function brand()
    {
        return $this->belongsTo('App\brand');
    }

    public function colors()
    {
        return $this->hasMany('App\colors');
    }
}
