<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Room;

class Building extends Model
{

    public function site()
    {
        return $this->belongsTo('App\Site');
    }

    public function address()
    {
        return $this->belongsTo('App\Address');
    }
    
    public function contact()
    {
        return $this->belongsTo('App\Contact');
    }

    public function rooms()
    {
        return $this->hasMany('App\Room');
    }

    public function getAddress()
    {
        $bldgaddress = $this->address;
        if($bldgaddress)
        {
            return $bldgaddress;
        }
        $siteaddress = $this->site->address;
        if($siteaddress)
        {
            return $siteaddress;
        }
    }

    public function getDefaultRoom()
    {
        return Room::find($this->default_room_id);
    }

    public function get911Contact()
    {
        $bldgcontact = $this->contact;
        if($bldgcontact)
        {
            return $bldgcontact;
        }
        $sitecontact = $this->site->contact;
        if($sitecontact)
        {
            return $sitecontact;
        }
    }

    public function getCoordinates()
    {
        if($this->lat && $this->lon)
        {
            $coordinates = $this->lat . "," . $this->lon;
        }
        $loc = $this->getAddress()->getServiceNowLocation();
        if($loc)
        {
            $coordinates = $loc->latitude . "," . $loc->longitude;
        }
        return $coordinates;
    }

}
