<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class colors extends Model
{
    protected $fillable = [
        'name', 'code','icon','collections_id'
    ];
    public function brand()
    {
        return $this->belongsTo('App\collections');
    }
}
