<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\TeamsLocation;
use App\Collections\RoomCollection;
use App\TMS;
use \EmergencyGateway\Address as EgwAddress;


class Room extends Model
{
    protected $casts = [
        'data'  =>  'json',
    ];

    public function newCollection(array $models = []) 
    { 
       return new RoomCollection($models); 
    }

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

    public function getTeamsLocation()
    {
        return TeamsLocation::find($this->teams_location_id);
    }

    public function isDefaultRoomInDefaultBuilding()
    {
        if(($this->building->default_room_id == $this->id) && ($this->building->site->default_building_id == $this->building->id))
        {
            return true;
        } else {
            return false;
        }
    }

    public function isDefaultRoom()
    {
        if($this->building->default_room_id == $this->id)
        {
            return true;
        } else {
            return false;
        }
    }

 /*    public function syncTeamsLocation()
    {
        print "ROOM:syncTeamsLocation()\n";
        $teamsLocationId = $this->teams_location_id;
        if(!$teamsLocationId)
        {
            print "TEAMS LOCATION ID not found....\n";
            if($this->isDefaultRoomInDefaultBuilding())
            {
                print "DEFAULT ROOM of DEFAULT BUILDING for site {$this->building->site->name} detected...  finding TEAMS DEFAULT LOCATION...\n";
                $civic = $this->getAddress()->getTeamsCivic();
                if($civic)
                {
                    $location = $civic->getTeamsDefaultLocation();
                } else {
                    $error = "Failed to obtain TEAMS CIVIC!\n";
                    print $error;
                    throw new \Exception($error);                    
                }

                if(!$location)
                {
                    $error = "Failed to obtain TEAMS DEFAULT LOCATION!\n";
                    print $error;
                    throw new \Exception($error);
                } else {
                    $teamsLocationId = $location->locationId;
                    print "TEAMS DEFAULT LOCATION with ID {$teamsLocationId} found...\n";
                    $this->teams_location_id = $teamsLocationId;
                    $this->save();
                }
            } else {
                print "Creating a new TEAMS LOCATION...\n";
                $teamsLocationId = $this->createTeamsLocation();
            }
        }
        return $teamsLocationId;
    } */

    public function createTeamsLocation()
    {
        $civicId = $this->getAddress()->teams_civic_id;
        if(!$civicId)
        {
            $error = "Failed to obtain TEAMS CIVIC ID!\n";
            print $error;
            throw new \Exception($error);
        }
        $teamsloc = new TeamsLocation;
        $teamsloc->civicAddressId = $civicId;
        $teamsloc->location = $this->building->site->name . " - " . $this->building->name . " - " . $this->name;
        $teamsLocationId = $teamsloc->save();
        $this->teams_location_id = $teamsLocationId;
        $this->save();
        return $teamsLocationId;
    }

    //Attempt to fetch the E911Erl for this room using E911Erl ID (locatioin_id)
    //Returns an E911Erl object or NULL.
    public function getE911ErlById()
    {
        if(isset($this->data['E911Erl_id']))
        {
            return E911Erl::getById($this->data['E911Erl_id']);
        }
    }

    //Attempt to fetch the E911Erl for this room using E911Erl NAME (erl_id)
    //Returns an E911Erl object or NULL.
    public function getE911ErlByName()
    {
        $erl = E911Erl::getByName($this->generateErlName());
        if(!$erl)
        {
            return null;
        }
        if(!isset($this->data['E911Erl_id']))
        {
            $data = $this->data;
            $data['E911Erl_id'] = $erl->location_id;
            $this->data = $data;
            $this->save();
        }
        return $erl;
    }

    //Attempt to fetch the E911Erl for this room buy first using ID, then using NAME.
    //Returns an E911Erl object or NULL.
    public function getE911Erl()
    {
        $erl = $this->getE911ErlById();
        if($erl)
        {
            return $erl;
        }
        $erl = $this->getE911ErlByName();
        if($erl);
        {
            return $erl;
        }
    }

    //Generates a description for this Room
    //Returns and STRING.
    public function generateE911ErlLoc()
    {
        $return = "";
        $address = $this->getAddress();
        $building = $this->building;
        $site = $building->site;
        if(!$address)
        {
            return null;
        }
        $street2 = $address->getStreet2Attribute();
        if($street2)
        {
            $return .= $street2 . " - ";
        }
        $return .= $site->name . " - " . $this->building->name . " - " . $this->name;
        $return = substr($return, 0, 50);
        return $return;
    }

    //Generates a NAME in format SITENAME_ROOMID
    //Returns a STRING.
    public function generateErlName()
    {
        return $this->building->site->name . "_" . $this->id;        
    }

    //Generates an E911Erl formatted Address Array
    //Returns and Array.
    public function generateE911Address()
    {
        $rmaddress = $this->getAddress();
        $address = [
            "LOC"       => $this->generateE911ErlLoc(),
            "HNO"       => $rmaddress->street_number,
            "PRD"       => $rmaddress->predirectional,
            "RD"        => $rmaddress->street_name,
            "STS"       => $rmaddress->street_suffix,
            "POD"       => $rmaddress->postdirectional,
            "A3"        => $rmaddress->city,
            "A1"        => $rmaddress->state,
            "country"   => $rmaddress->country,            
            "PC"        => $rmaddress->postal_code,
        ];
        return $address;
    }

    public function generateValidationAddress()
    {
        $address = $this->getAddress();
        if(!$address)
        {
            return null;
        }
        $site = $this->building->site;
        if(!$site)
        {
            return null;
        }
        $street1REST = "";
        if($address['predirectional'])
        {
            $street1REST .= $address['PRD'] . " ";
        }
        if($address['street_name'])
        {
            $street1REST .= $address['street_name'];
        }
        if($address['street_suffix'])
        {
            $street1REST .= " " . $address['street_suffix'];
        }
        if($address['postdirectional'])
        {
            $street1REST .= " " . $address['postdirectional'];
        }
        return [
            'LOC'       => $this->generateE911ErlLoc(),
            'HNO'       => $address->street_number,
            'RD'        => $street1REST,
            "A3"        => $address->city,
            "A1"        => $address->state,
            "country"   => $address->country,            
            "PC"        => $address->postal_code,
            'NAM'       => $site->name,
        ];
    }

    public function validateAddress()
    {
        $addressobj = EgwAddress::fromArray($this->generateValidationAddress());
        $results = E911Erl::getEgw()->validateAddress($addressobj);
        if($results->status == 0)
        {
            return true;
        } else {
            return false;
        }
    }


    //Generates an ERL NAME and an ADDRESS and attempts to create an E911Erl.
    //If successful, it will add the E911Erl location_id to data['E911Erl_id'] property of the room.
    //returns an E911Erl Object.
    public function addE911Erl()
    {
        $erl = $this->getE911Erl();
        if($erl)
        {
            throw new \Exception('E911Erl already exists for this room!');
            return null;
        }
        $erlname = $this->generateErlName();
        $address = $this->generateE911Address();
        $elin = null;

        if($address['country'] == "CAN")
        {
            $elin = $this->getTMSElin();
            if(!$elin)
            {
                $elin = $this->reserveElin();
            }
            if(!$elin)
            {
                throw \Exception('Unable to find an ELIN for Canadian site!');
            }
        }

        $attempt = E911Erl::add($erlname, $address, $elin['number']);
        $erl = $this->getE911Erl();
        if($erl)
        {
            $data = $this->data;
            $data['E911Erl_id'] = $erl->location_id;
            $this->data = $data;
            $this->save();
        }
        return $erl;
    }

    //Attempt to locate and delete associated E911Erl.
    //returns TRUE/FALSE
    public function deleteE911Erl()
    {
        $erl = $this->getE911Erl();
        if($erl)
        {
            $erl->delete();
        }
        $erl2 = $this->getE911Erl();
        if(!$erl2)
        {
            $data = $this->data;
            unset($data['E911Erl_id']);
            $this->data = $data;
            $this->save();
            return true;
        } else {
            $data = $this->data;
            $data['E911Erl_id'] = $erl2->location_id;
            $this->data = $data;
            $this->save();
            return false;
        }
    }

    public function validateE911Erl()
    {
        $mapping = [
            "LOC"       => "address_type",
            "HNO"       => "hno",
            "PRD"       => "prd",
            "RD"        => "rd",
            "STS"       => "sts",
            "POD"       => "pod",
            "A3"        => "city",
            "A1"        => "state",
            "country"   => "country",
            "PC"        => "zip_code",
       
        ];
        $erl = $this->getE911Erl();
        if(!$erl)
        {
            return null;
        }
        $address = $this->generateE911Address();
        $matches = 1;
        foreach($mapping as $room_key => $erl_key)
        {
            if(strtoupper($address[$room_key]) != strtoupper($erl->$erl_key))
            {
                $matches = 0;
                break;
            }
        }
        return $matches;
    }

    //Create a new TMS object to access the TMS system.
    //returns a TMS object.
    public function getTMS()
    {
        return new TMS(env('TMS_URL'),env('TMS_USERNAME'),env('TMS_PASSWORD'));        
    }

    public function getTMSElin()
    {
        $elins = $this->getTMS()->getCaElins();
        $elin = $elins->where('name',$this->generateErlName())->first();
        if($elin)
        {
            return $elin;
        }
    }

    public function reserveElin()
    {
        return $this->getTMS()->reserveCaElin($this->generateErlName());
    }

    public function releaseElin()
    {
        $elin = $this->getTMSElin();
        if(!$elin)
        {
            return null;
        }
        return $this->getTMS()->releaseCaElin($elin['id']);
    }

    public function purge()
    {
        //Delete teams location
        $teamslocation = $this->getTeamsLocation();
        if($teamslocation)
        {
            $teamslocation->delete();
        }
        //Clear TMS ELIN
        $this->releaseElin();
        //Delete E911 ERL
        $e911erl = $this->getE911Erl();
        if($e911erl)
        {
            $e911erl->purge();
        }
        //delete self
        $this->delete();
    }

}
