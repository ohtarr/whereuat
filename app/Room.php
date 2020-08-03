<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Room extends Model
{
    public function building()
    {
        return $this->belongsTo('App\Building');
    }

    public function getAddress()
    {
        return $this->building->getAddress();
    }

    public function get911Contact()
    {
        return $this->building->get911Contact();
    }

    public function getCoordinates()
    {
        return $this->building->getCoordinates();
    }
}
