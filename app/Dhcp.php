<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use GuzzleHttp\Client as GuzzleHttpClient;
use App\Site;
use App\TeamsSubnet;
use IPv4\SubnetCalculator as NetCalc;

class Dhcp extends Model
{
    protected $guarded =[];

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
        return collect($scopes);
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

    public static function findSiteScopes($sitename)
    {
        foreach(static::all() as $scope)
        {
            if(stripos($scope['name'],$sitename) !== false)
            {
                $scopes[] = $scope;
            }
        }
        return collect($scopes);
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

}