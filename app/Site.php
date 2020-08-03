<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\ServiceNowLocation;
use App\ServiceNowUser;
use App\Address;
use App\Contact;
use App\Building;

class Site extends Model
{

    public function address()
    {
        return $this->belongsTo('App\Address');
    }

    public function contact()
    {
        return $this->belongsTo('App\Contact');
    }

    public function buildings()
    {
        return $this->hasMany('App\Building');
    }

    public function getRooms()
    {
        
    }

    public function getServiceNowLocation()
    {
        if($this->loc_sys_id)
        {
            return ServiceNowLocation::find($this->loc_sys_id);
        }
    }

    public function getAddress()
    {
        return $this->address;
    }

    public function getDefaultBuilding()
    {
        return Building::find($this->default_building_id);
    }

    public function getCoordinates()
    {
        $loc = $this->getServiceNowLocation();
        if($loc)
        {
            $coordinates = $loc->latitude . "," . $loc->longitude;
        }
        return $coordinates;
    }

    public function getBusinessContact()
    {
        $location = $this->getServiceNowLocation();
        return ServiceNowUser::find($location->contact['value']);
    }

    public function getItContact()
    {
        $location = $this->getServiceNowLocation();
        return ServiceNowUser::find($location->u_on_site_contact['value']);
    }

    public function get911Contact()
    {
        return $this->contact;
    }

}
