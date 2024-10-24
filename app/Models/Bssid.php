<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use GuzzleHttp\Client as GuzzleHttpClient;
use App\Models\Site;
use App\Models\Room;
use App\Models\TeamsWap;

class Bssid extends Model
{
    protected $guarded =[];

    public $cache;

    public $fieldMapping = [
        'bssid' => 'bssid',
        'name'  => 'description',
    ];

    public static function all($columns = [])
    {
        $url = env('SSID_URL');
        $client = new GuzzleHttpClient();
        $response = $client->request("GET", $url);
        //get the body contents and decode json into an array.
        $array = json_decode($response->getBody()->getContents(), true);
        foreach($array as $item)
        {
            unset($tmp);
            if($item['name'] && $item['bssid'])
            {
                $tmp['name'] = strtoupper($item['name']);
                $tmp['bssid'] = strtoupper(str_replace(":", "-", $item['bssid']));
                if(isset($item['neighbor']['snmp']['room']))
                {
                    $tmp['room_id'] = $item['neighbor']['snmp']['room'];
                }
                if(isset($item['neighbor']['name']))
                {
                    $tmp['neighbor'] = $item['neighbor']['name'];
                }
                $object = self::make($tmp);
                $bssids[] = $object;
            }
        }
        return collect($bssids);
    }

    public static function find($bssid)
    {
        return self::all()->where('bssid',$bssid)->first();
    }

    public static function where($key,$value)
    {
        return Bssid::all()->where($key,$value);
    }

    public function cacheAll($force = false)
    {
        if($force || !$this->cache)
        {
            $this->cache = $this->all();
        }
        return $this->cache;
    }

    public function cacheFind($id)
    {
        return $this->cacheAll()->where('bssid',$id)->first();
    }

    public function getSiteCode()
    {
        if($this->neighbor)
        {
            $sitecode = strtoupper(substr($this->neighbor,0,8));
        } else {
            $sitecode = strtoupper(substr($this->name,0,8));;
        }
        return $sitecode;
    }

    public function getSite()
    {
        return Site::where("name",$this->getSiteCode())->first();
    }

    public function getSiteAttribute()
    {
        return $this->getSite();
    }

    public function getRoom()
    {
        $room = null;
        if($this->room_id)
        {
            $room = Room::find($this->room_id);
        }
        
        if(!$room)
        {
            $site = $this->getSite();
            if($site)
            {
                $room = $this->getSite()->defaultBuilding->defaultRoom;
            }
        }

        return $room;
    }

    public function getRoomAttribute()
    {
        return $this->getRoom();
    }

    public function getTeamsLocationId()
    {
        $room = $this->getRoom();
        if($room)
        {
            return $room->teams_location_id;
        }

    }

    public function createOrUpdateTeamsBssid()
    {
        $room = $this->getRoom();
        if($room)
        {
            $teamsLocationId = $this->getTeamsLocationId();
            if($teamsLocationId)
            {
                $wap = new TeamsWap;
                $wap->bssid = $this->bssid;
                $wap->description = $this->name;
                $wap->locationId = $teamsLocationId;
                $wap->save();
            } else {
                throw new \Exception("Unable to find TEAMS LOCATION");
            }
        } else {
            throw new \Exception("Unable to find ROOM");
        }
    }

    public function getTeamsWap()
    {
        return TeamsWap::find($this->bssid);
    }

    public function validateTeamsWap($teamsWap = null)
    {
        $matches = true;
        if(!$teamsWap)
        {
            $teamsWap = $this->getTeamsWap();
        }
        if(!$teamsWap->bssid)
        {
            $matches = false;
        }
        foreach($this->fieldMapping as $bssidKey => $teamsKey)
        {
            if($this->$bssidKey != $teamsWap->$teamsKey)
            {
                $matches = false;
            }
        }
        if($this->getTeamsLocationId() != $teamsWap->locationId)
        {
            $matches = false;
        }
        return $matches;
    }

    public static function formatMac($mac)
    {
        $mac = str_replace(":", '', $mac);
        $mac = str_replace("-", '', $mac);
        $mac = str_replace(".", '', $mac);
        $mac = strtolower($mac);
        return $mac;
    }

}
