<?php

namespace App;

use App\Gizmo;
use App\TeamsCivic;
use App\TeamsSwitch;
use App\Room;

class TeamsLocation extends Gizmo
{
    public static $key = "locationId";
    //public static $base_url = "";
    public static $all_url_suffix = "/api/e911/csonlinelislocations";
    public static $get_url_suffix = "/api/e911/csonlinelislocation";
    public static $find_url_suffix = "/api/e911/csonlinelislocation";
    public static $save_url_suffix = "/api/e911/csonlinelislocation/new";

    public $queryable = [ 
        "CivicAddressId",
        "City",
        "Location",
        "LocationId",
        "CountyOrRegion",
    ];

    public $saveable = [
        "Location",
    ];

    public function getTeamsCivic()
    {
        if($this->civicAddressId)
        {
            return TeamsCivic::find($this->civicAddressId);
        }
        print "No civicAddressId found!\n";
        return false;
    }

    public function getTeamsSwitches()
    {
        return TeamsSwitch::all()->where('locationId', $this->locationId);
    }

    public function getRoom()
    {
        return Room::where('teams_location_id',$this->locationId)->first();
    }

}
TeamsLocation::init();