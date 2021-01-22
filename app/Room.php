<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\TeamsLocation;
use App\Collections\RoomCollection;

class Room extends Model
{
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

}
