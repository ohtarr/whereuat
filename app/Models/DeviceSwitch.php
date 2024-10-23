<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use GuzzleHttp\Client as GuzzleHttpClient;
use App\Models\TeamsSwitch;
use App\Models\Site;
use App\Models\Room;
use App\Collections\SwitchCollection;
use App\Models\E911Switch;

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
        $room = $this->getRoom();
        if(!$room)
        {
            return null;
        }
        $erl = $room->getE911ErlByName();
        if(!$erl)
        {
            return null;
        }
        E911Switch::add($this->ip, $this->vendor, $erl->erl_id, $this->name);
    }

    public function validateE911Switch()
    {
        $mapping = [
            'ip'        =>  'switch_ip',
            'name'      =>  'switch_description',
            'vendor'    =>  'switch_vendor',
        ];
        $room = $this->getRoom();
        if(!$room)
        {
            print "NO ROOM FOUND!\n";
            return null;
        }
        $erl = $room->getE911Erl();
        if(!$erl)
        {
            print "NO ERL FOUND!\n";
            return null;
        }
        $e911switch = $this->getE911Switch();
        if(!$e911switch)
        {
            print "NO E911SWITCH FOUND!\n";
            return null;
        }
        $matches = 1;
        foreach($mapping as $deviceswitch_key => $e911switch_key)
        {
            if($this->$deviceswitch_key != $e911switch->$e911switch_key)
            {
                print "NO MATCH!\n";
                $matches = 0;
                break;
            }
        }
        if($matches == 1)
        {
            if($room->generateErlName() != $erl->erl_id)
            {
                print $room->generateErlName() . "\n";
                print $erl->erl_id . "\n";
                print "ERL NAME DOES NOT MATCH!\n";
                $matches = 0;
            }
        }
        return $matches;
    }

    public function updateE911Switch()
    {
        $e911switch = $this->getE911Switch();
        if(!$e911switch)
        {
            return null;
        }
        $e911erl = $this->getRoom()->getE911Erl();
        if(!$e911erl)
        {
            return null;
        }
        $e911switch->modify($this->ip, $this->vendor, $e911erl->erl_id, $this->name);
    }
}
