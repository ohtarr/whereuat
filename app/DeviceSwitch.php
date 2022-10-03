<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use GuzzleHttp\Client as GuzzleHttpClient;
use App\TeamsSwitch;
use App\Site;
use App\Room;
use App\Collections\SwitchCollection;
use App\E911Switch;

class DeviceSwitch extends Model
{
    protected $guarded =[];

    public $cache;

    public function newCollection(array $models = [])
    { 
       return new SwitchCollection($models); 
    } 

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
        return new SwitchCollection($switches);
    }

    public static function find($key)
    {
        $objects = self::all();
        $return = $objects->find($key);
        //$return = $objects->where('ip',$search)->first();
        return $return;
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

    public function withLocation()
    {
        $room = $this->getRoom();
        if($room)
        {
            $room->building = $room->building;
            $room->building->site = $room->building->site;
            $this->room = $room;
        }
        return $this;
    }

    public function getE911Switch()
    {
        return E911Switch::all()->where('switch_ip',$this->ip)->first();
    }

    public function addE911Switch()
    {
        $erl = $this->getRoom()->getE911Erl();
        if(!$erl)
        {
            return null;
        }
        E911Switch::add($this->ip, $this->vendor, $erl, $this->name);
    }
}
