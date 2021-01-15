<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\ServiceNowLocation;
use App\ServiceNowUser;
use App\Address;
use App\Contact;
use App\Building;
use App\Dhcp;

class Site extends Model
{
    public $loc;

/*     //WHEREUAT_ADDRESS to SERVICENOWLOCATION field mappings
    public $addressMapping = [
        'street_number'             => 'u_street_number',
        'predirectional'            => 'u_street_predirectional',
        'street_name'               => 'u_street_name',
        'street_suffix'             => 'u_street_suffix',
        'postdirectional'           => 'u_street_postdirectional',
        'secondary_unit_indicator'  => 'u_secondary_unit_indicator',
        'secondary_number'          => 'u_secondary_number',
        'city'                      => 'city',
        'state'                     => 'state',
        'postal_code'               => 'zip',
        'country'                   => 'country',
        'latitude'                  => 'latitude',
        'longitude'                 => 'longitude',
    ]; */

/*     public function address()
    {
        return $this->belongsTo('App\Address');
    } */

    public function getAddressAttribute()
    {
        return $this->defaultBuilding->address;
    }

/*     public function contact()
    {
        return $this->belongsTo('App\Contact');
    } */

    public function getContactAttribute()
    {
        return $this->defaultBuilding->contact;
    }

    public function buildings()
    {
        return $this->hasMany('App\Building');
    }

    public function defaultBuilding()
    {
        return $this->hasOne('App\Building', 'id', 'default_building_id');
    }

    public function getServiceNowLocation()
    {
        if(!$this->loc && $this->loc_sys_id) {
            $this->loc = ServiceNowLocation::find($this->loc_sys_id);
        }
        return $this->loc;
    }

    public function getServiceNowLocationAttribute()
    {
        return $this->getServiceNowLocation();
    }

    public function getAllRooms()
    {
        foreach($this->buildings as $building)
        {
            foreach($building->rooms as $room)
            {
                $array[] = $room;
            }

        }
        return collect($array);
    }

    public function getRoomsAttribute()
    {
        return $this->getAllRooms();
    }

    public function getAddress()
    {
        return $this->address;
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

    public function getContact911Attribute()
    {
        return $this->get911Contact();
    }

    public function syncAdd()
    {
        $this->syncAddress();
        $this->syncContact();
        $this->syncDefaultBuilding();
        return $this->refresh();
    }

    public function syncAddress()
    {
        if(!$this->addressIsSynced())
        {
            $address = $this->createOrUpdateAddress();
        } else {
            $address = $this->getAddress();
        }
        return $address;

 /*        print "Site::syncAddress()\n";
        $address = $this->address;
        if(!$address)
        {
            print "ADDRESS not found, creating new...\n";
            $address = $this->createNewAddress();
            if(!$address)
            {
                $error = "Failed to create ADDRESS!\n";
                print $error;
                throw new \Exception($error);
            }
            print "ADDRESS with ID {$address->id} was created...\n";
        }
        print "ADDRESS with ID {$address->id} was found...\n";
        return $address; */
    }

    public function getScopesAttribute()
    {
        return Dhcp::findSiteScopes($this->name);
    }

    public function syncContact()
    {
        print "Obtaining existing CONTACT...\n";
        $contact = $this->contact;
        if(!$contact)
        {
            print "CONTACT doesn't exist, creating...\n";
            $contact = $this->createNewContact();
            if(!$contact)
            {
                $error = "Failed to create CONTACT!\n";
                print $error;
                throw new \Exception($error);
            }
            print "CONTACT with ID {$contact->id} was created...\n";
        }
        print "CONTACT with ID {$contact->id} was found...\n";
        return $contact;
    }

    public function syncDefaultBuilding()
    {
        print "Obtaining DEFAULT BUILDING...\n";
        $defaultBuilding = $this->defaultBuilding;
        if(!$defaultBuilding)
        {
            print "DEFAULT BUILDING was not found... creating new...\n";
            $defaultBuilding = $this->createDefaultBuilding();
            if(!$defaultBuilding)
            {
                $error = "Failed to get create DEFAULT BUILDING!\n";
                print $error;
                throw new \Exception($error);
            }
            print "DEFAULT BUILDING with ID {$defaultBuilding->id} was created...\n";
        }
        print "DEFAULT BUILDING with ID {$defaultBuilding->id} was found...\n";
        return $defaultBuilding;
    }

    public function createNewContact()
    {
        $econtact = $this->getServiceNowLocation()->get911Contact();
        if(!$econtact)
        {
            $error = "Failed to get ServiceNowLocation 911Contact!\n";
            print $error;
            throw new \Exception($error);
            return null;
        }
        $contact = new Contact;
        $contact->name = $this->name . "_911_CONTACT";
        $contact->description = "Default Contact created by system.";
        $contact->phone = preg_replace('/\D+/', '', $econtact->phone);
        $contact->email = $econtact->email;
        $contact->save();
        $defaultBuilding = $this->defaultBuilding;
        $defaultBuilding->contact_id = $contact->id;
        $defaultBuilding->save();
        return $contact;
    }

/*     public function createNewAddress()
    {
        $serviceNowLocation = $this->getServiceNowLocation();
        if($serviceNowLocation)
        {
            $address = new Address;
            foreach($this->addressMapping as $addressKey => $snowKey)
            {
                $address->$addressKey = $serviceNowLocation->$snowKey;
            }
            $address->save();

            $this->address_id = $address->id;
            $this->save();

            return $address;
        } else {
            return null;
        }
    }
 */
    public function createOrUpdateAddress()
    {
        $serviceNowLocation = $this->getServiceNowLocation();
        $defaultBuilding = $this->defaultBuilding;
        if(!$defaultBuilding)
        {
            return null;
        }
        if($serviceNowLocation)
        {
            if($this->address)
            {
                $address = $this->address;
            } else {
                $address = new Address;
            }
            foreach($address->snowAddressMapping as $addressKey => $snowKey)
            {
                $address->$addressKey = $serviceNowLocation->$snowKey;
            }
            $address->save();

            $defaultBuilding->address_id = $address->id;
            $defaultBuilding->save();

            return $address;
        }
    }

    public function createDefaultBuilding()
    {
        $building = new Building;
        $building->name = "DEFAULT_BUILDING";
        $building->description = "{$this->name}";
        $building->site_id = $this->id;
        $building->save();
        //$building->syncAdd();
        //$building->createDefaultRoom();
        $this->default_building_id = $building->id;
        $this->save();
        return $building;
    }

    public function addressIsSynced()
    {
        $address = $this->address;
        $snowloc = $this->getServiceNowLocation();
        if(!$address || !$snowloc)
        {
            return false;
        }
        $matches = true;

        foreach($address->snowAddressMapping as $addressKey => $snowKey)
        {
            if($address->$addressKey != $snowloc->$snowKey)
            {
                $matches = false;
                break;
            }
        }

        return $matches;
    }

}
