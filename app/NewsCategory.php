<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class NewsCategory extends Model
{
    public function news()
    {
        return $this->hasOne('App\News');

    }
}
