<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Log;
use App\Room;
use App\Address;

class Building extends Model
{

    protected $hidden = ['buildingAddress'];

    public function site()
    {
        return $this->belongsTo('App\Site');
    }

    public function buildingAddress()
    {
        return $this->belongsTo('App\Address','address_id','id');
    }
    
    public function contact()
    {
        return $this->belongsTo('App\Contact');
    }

    public function rooms()
    {
        return $this->hasMany('App\Room');
    }

    public function defaultRoom()
    {
        return $this->hasOne('App\Room','id','default_room_id');
    }

    public function getAddressAttribute()
    {
        return $this->getAddress();
    }

    public function getAddress()
    {
        if($this->buildingAddress)
        {
            return $this->buildingAddress;
        }
        return $this->site->defaultBuilding->address;
    }

    public function isDefaultBuilding()
    {
        if($this->site->defaultBuilding->id == $this->id)
        {
            return true;
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
        $loc = $this->site->getServiceNowLocation();
        if($loc)
        {
            $coordinates = $loc->latitude . "," . $loc->longitude;
            return $coordinates;
        }
    }

    public function syncDefaultRoom()
    {
        $msg = get_class() . "::" . __FUNCTION__ . "\n";
        print $msg;
        Log::info($msg);
        $defaultRoom = $this->defaultRoom;
        if(!$defaultRoom)
        {
            print "DEFAULT ROOM not found, creating new...\n";
            $defaultRoom = $this->createDefaultRoom();
            if(!$defaultRoom)
            {
                throw new \Exception("Failed to create DEFAULT ROOM!");
            }
            print "DEFAULT ROOM with ID {$defaultRoom->id} was created...\n";
        }
        print "DEFAULT ROOM with ID {$defaultRoom->id} was found...\n";
        return $defaultRoom;
    }

    public function createDefaultRoom()
    {
        $room = new Room;
        $room->name = "DEFAULT_ROOM";
        $room->description = "Default Room created for site {$this->site->name}";
        $room->building_id = $this->id;
        $room->save();
        $this->default_room_id = $room->id;
        $this->save();
        return $room;
    }

}
