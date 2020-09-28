<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use GuzzleHttp\Client as GuzzleHttpClient;
use App\TeamsSwitch;
use App\Site;
use App\Room;

class DeviceSwitch extends Model
{
    protected $guarded =[];

    public $cache;

    public static function all($columns = [])
    {
        $url = env('SWITCHES_URL');
        $client = new GuzzleHttpClient();
        $response = $client->request("GET", $url);
        //get the body contents and decode json into an array.
        $array = json_decode($response->getBody()->getContents(), true);
        foreach($array as $item)
        {
            if($item['mac'])
            {
                $object = self::make($item);
                $switches[] = $object;
            }
        }
        return collect($switches);
    }

    public function cacheAll($force = false)
    {
        if($force || !$this->cache)
        {
            $this->cache = $this->all();
        }
        return $this->cache;
    }

    public function getSiteCode()
    {
        return strtoupper(substr($this->name,0,8));
    }

    public function getSite()
    {
        return Site::where('name', $this->getSiteCode())->first();
    }

    public function getRoom()
    {
        if(isset($this->snmp_loc['json']['room']))
        {
            $room_id = $this->snmp_loc['json']['room'];
            $room = Room::find($room_id);
            if($room)
            {
                return $room;
            }
        }
        $site = $this->getSite();
        if($site)
        {
            $room = $site->defaultBuilding->defaultRoom;
            return $room;
        }

    }

    public function createTeamsSwitch()
    {
        $room = $this->getRoom();
        if($room)
        {
            print "Found ROOM ID {$room->id}...\n";
            $teamsloc = $room->getTeamsLocation();
        }
        if($teamsloc)
        {
            print "Found TEAMS LOCATION ID {$teamsloc->locationId}...  Creating new TEAMS SWITCH...\n";
            $switch = new TeamsSwitch;
            $switch->chassisId = $this->mac;
            $switch->Description = $this->name;
            $switch->LocationId = $teamsloc->locationId;
            $switch->save();
        }
    }

    public function setTeamsSwitchFromTeamsLocationId($locationId)
    {
        $switch = new TeamsSwitch;
        $switch->chassisId = $this->mac;
        $switch->Description = $this->name;
        $switch->LocationId = $locationId;
        $switch->save();
    }

}
