<?php

namespace App;

use App\Gizmo;

class TeamsLocation extends Gizmo
{
    public static $key = "LocationId";
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
}
TeamsLocation::init();