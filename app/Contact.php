<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Contact extends Model
{
    public function sites()
    {
        return $this->hasMany('App\Site');
    }

    public function buildings()
    {
        return $this->hasMany('App\Building');
    }

}
