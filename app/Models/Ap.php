<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use GuzzleHttp\Client as GuzzleHttpClient;
use App\Models\Site;
use App\Models\Room;
use App\Models\TeamsWap;
use App\Collections\ApCollection;

class Ap extends Model
{
    protected $primaryKey = 'mac'; // or null
    public $incrementing = false;

    protected $guarded =[];

    public $cache;

    public function newCollection(array $models = []) 
    { 
       return new ApCollection($models); 
    } 

    public static function all($columns = [])
    {
        $url = env('AP_URL');
        $client = new GuzzleHttpClient();
        $response = $client->request("GET", $url);
        //get the body contents and decode json into an array.
        $array = json_decode($response->getBody()->getContents(), true);
        //print_r($array);
        foreach($array as $item)
        {
            $object = self::make($item);
            $aps[] = $object;
        }
        return new ApCollection($aps);
    }

/*     public static function where($key,$value)
    {
        return self::all()->where($key,$value);
    } */

    public function getSiteCode()
    {
        if($this->neighbor['name'])
        {
            $sitecode = strtoupper(substr($this->neighbor['name'],0,8));
        } else {
            $sitecode = strtoupper(substr($this->name,0,8));;
        }
        return $sitecode;
    }

    public static function find($search)
    {
        $aps = self::all();
        $return = $aps->where('mac',$search)->first();
/*         $return = $aps->filter(function ($item) use ($search) {
            return false !== stripos(strtolower($item->mac), strtolower($search));
        }); */
        return $return;
    }

/*     public static function where($key,$value)
    {
        $aps = self::all();
        $return = $aps->filter(function ($item) use ($key,$value) {
            return false !== stripos(strtolower($item->$key), strtolower($value));
        });
        return $return;
    }

    public static function whereBssid($key,$value)
    {
        $aps = self::all();
        $return = $aps->filter(function ($item) use ($key,$value) {
            if(isset($item->bssids))
            {
                foreach($item->bssids as $bssid)
                {
                    if($bssid[$key] == $value)
                    {
                        return true;
                    }
                }
            }
        });
        return $return;
    } */

    public static function findBssid($search)
    {
        $aps = self::all();
        $return = $aps->filter(function ($item) use ($search) {
            if(isset($item->bssids))
            {
                foreach($item->bssids as $bssid)
                {
                    if($bssid['essid'] == $search)
                    {
                        return true;
                    }
                }
            }
        });
        return $return;
    }

    public static function findNeighbor($search)
    {
        $aps = self::all();
        $return = $aps->filter(function ($item) use ($search) {
            if(isset($item->neighbor))
            {
                return false !== stripos(strtolower($item->neighbor['name']), strtolower($search));
            }
        });
        return $return;
    }

    public function getSite()
    {
        return Site::where("name",$this->getSiteCode())->first();
    }

    public function getRoom()
    {
        $room = null;
        if(isset($this->neighbor['snmp']['room']))
        {
            if($this->neighbor['snmp']['room'])
            {
                $room = Room::find($this->neighbor['snmp']['room']);
            }
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

    public function withSite()
    {
        $site = $this->getSite();
        if($site)
        {
            $site->defaultbuilding = $site->defaultBuilding;
            $site->defaultbuilding->address = $site->defaultBuilding->address;
            $this->site = $site;
        }
        return $this;
    }

    public function withLocation()
    {
        $room = $this->getRoom();
        if($room)
        {
            $room->building->site;
            $room->building->address;
            $this->room = $room;
        }
        return $this;
    }

    public function withoutNeighbor()
    {
        unset($this->neighbor);
        return $this;
    }

    public function withoutBssids()
    {
        unset($this->bssids);
        return $this;
    }

}
