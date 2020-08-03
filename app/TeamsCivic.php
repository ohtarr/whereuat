<?php

namespace App;

use App\Gizmo;

class TeamsCivic extends Gizmo
{
    //primary_Key of model.
    public static $key = "civicAddressId";
    //url suffix to access ALL endpoint
    public static $all_url_suffix = "/api/e911/csonlinecivicaddresses";
    //url suffix to access GET endpoint
    public static $get_url_suffix = "/api/e911/csonlinecivicaddress";
    //url suffix to access FIND endpoint
    public static $find_url_suffix = "/api/e911/csonlinecivicaddress";
    //url suffix to access the SAVE endpoint
    public static $save_url_suffix = "/api/e911/csonlinecivicaddress/new";

    //fields that are queryable by this model.
    public $queryable = [ 
        "CivicAddressId",
        "City",
    ];

    //fields that are EDITABLE by this model.
    public $saveable = [
        "civicAddressId",
        "CompanyName",
        "CompanyTaxId",
        "HouseNumber",
        "HouseNumberSuffix",
        "StreetName",
        "StreetSuffix",
        "PreDirectional",
        "PostDirectional",
        "City",
        "CityAlias",
        "StateOrProvince",
        "CountryOrRegion",
        "PostalCode",
        "Description",
        "Latitude",
        "Longitude",
        "Elin",
    ];
}
//Initialize the model with the BASE_URL from env.
TeamsCivic::init();