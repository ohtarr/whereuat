<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use GuzzleHttp\Client as GuzzleHttpClient;
use App\Models\Site;
use App\Models\TeamsSubnet;
use IPv4\SubnetCalculator as NetCalc;
use App\Collections\DhcpCollection;

class Dhcp extends Model
{
    protected $primaryKey = 'scopeID'; // or null
    public $incrementing = false;

    protected $guarded =[];

    public function newCollection(array $models = [])
    { 
       return new DhcpCollection($models); 
    } 

    public static function all($columns = [])
    {
        $url = env('DHCP_URL');
        $client = new GuzzleHttpClient();
        $response = $client->request("GET", $url);
        //get the body contents and decode json into an array.
        $array = json_decode($response->getBody()->getContents(), true);
        foreach($array as $item)
        {
            $object = self::make($item);
            $scopes[] = $object;
        }
        return new DhcpCollection($scopes);
    }

    public static function find($scopeID)
    {
        return self::all()->where('scopeID',$scopeID)->first();
    }

    public function cacheAll($force = false)
    {
        if($force || !$this->cache)
        {
            $this->cache = $this->all();
        }
        return $this->cache;
    }

    public function findSite()
    {
        $sites = Site::all();
        foreach($sites as $site)
        {
            if(stripos($this->name,$site->name) !== false)
            {
                return $site;
            }
        }
    }

    public function getSite()
    {
        return $this->findSite();
    }

    public static function findSiteScopes($sitename)
    {
        $scopes = [];
        foreach(static::all() as $scope)
        {
            if(stripos($scope['name'],$sitename) !== false)
            {
                $scopes[] = $scope;
            }
        }
        return new DhcpCollection($scopes);
    }

    public function createTeamsSubnet()
    {
        print "Creating TeamsSubnet from DHCP scope {$this->scopeID}\n";
        $site = $this->findSite();
        if($site)
        {
            $teamsSubnet = new TeamsSubnet;
            $teamsSubnet->subnet = $this->scopeID;
            $teamsSubnet->description = $site->name;
            $teamsSubnet->locationId = $site->defaultBuilding->defaultRoom->teams_location_id;
            $teamsSubnet->save();
            return $teamsSubnet;
        } else {
            print "No site found, unable to create TeamsSubnet!\n";
        }
    }

    public function isInScope($ip)
    {
        $netcalc = new NetCalc($this->scopeID, self::mask2cidr($this->subnetMask));
        return $netcalc->isIPAddressInSubnet($ip);
    }

    public static function mask2cidr($mask)
    {
        $long = ip2long($mask);
        $base = ip2long('255.255.255.255');
        $float = 32-log(($long ^ $base)+1,2);
        return intval($float);
    }

    public static function findScope($ip)
    {
        $dhcp = new static;
        foreach($dhcp->cacheAll() as $scope)
        {
            if($scope->isInScope($ip))
            {
                return $scope;
            }
        }
    }

    /* public static function allWithSites($columns = [])
    {
        $scopes = self::all();
        foreach($scopes as $scope)
        {
            $newscope = $scope;
            $site = $scope->findSite();
            if($site)
            {
                $site->load('address');
                $newscope['site'] = $site;
            }
            $newscopes[] = $newscope;
        }
        return collect($newscopes);
    } */

    public function withSite()
    {
        $site = $this->findSite();
        if($site)
        {
            $site->defaultbuilding = $site->defaultBuilding;
            $site->defaultbuilding->address = $site->defaultBuilding->address;
            $this->site = $site;
        }
        return $this;
    }

    public function withoutReservations()
    {
        unset($this->reservations);
        return $this;
    }

    public function withoutFailover()
    {
        unset($this->failover);
        return $this;
    }

    public function withoutOptions()
    {
        unset($this->dhcpOptions);
        return $this;
    }

}
