<?php

namespace App;

use App\TeamsCivic;
use App\TeamsLocation;
use App\TeamsSwitch;
use App\TeamsWap;
use App\ServiceNowLocation;
use App\DeviceSwitch;
use App\Bssid;

class Cache
{
    public $teamscivics;
    public $teamslocations;
    public $teamsswitches;
    public $teamswaps;
    public $switches;
    public $snowlocations;
    public $bssids;



    public function getServiceNowLocations()
    {
        print "Cache::getServiceNowLocations()\n";
        if(!$this->snowlocations)
        {
            $this->snowlocations = ServiceNowLocation::where('u_network_demob_date',"")->get();
        }
        return $this->snowlocations;
    }

    public function getServiceNowLocation($sys_id)
    {
        return $this->getServiceNowLocations()->where('sys_id',$sys_id)->first();
    }

    public function getTeamsCivics()
    {
        print "Cache::GetTeamsCivics()\n";
        if(!$this->teamscivics)
        {
            $this->teamscivics = TeamsCivic::all();
        }
        return $this->teamscivics;
    }

    public function getTeamsLocations()
    {
        print "Cache::getTeamsLocations()\n";
        if(!$this->teamslocations)
        {
            $this->teamslocations = TeamsLocation::all();
        }
        return $this->teamslocations;
    }

    public function getTeamsCivic($civicAddressId)
    {
        return $this->getTeamsCivics()->where('civicAddressId',$civicAddressId)->first();
    }

    public function getTeamsLocation($locationId)
    {
        return $this->getTeamsLocations()->where('locationId',$locationId)->first();
    }

    public function getTeamsDefaultLocation($civicAddressId)
    {
        return $this->getTeamsLocations()->where('civicAddressId',$civicAddressId)->whereNull('location')->first();
    }

    public function getTeamsNonDefaultLocations($civicAddressId)
    {
        return $this->getTeamsLocations()->where('civicAddressId',$civicAddressId)->whereNotNull('location')->values();
    }

    public function getSwitches()
    {
        print "Cache::getSwitches()\n";
        if(!$this->switches)
        {
            $this->switches = DeviceSwitch::all();
        }
        return $this->switches;
    }

    public function getSwitch($mac)
    {
        return $this->getSwitches()->where('mac',$mac)->first();
    }

    public function getBssids()
    {
        print "Cache::getBssids()\n";
        if(!$this->bssids)
        {
            $this->bssids = Bssid::all();
        }
        return $this->bssids;
    }

    public function getBssid($bssid)
    {
        return $this->getBssids()->where('bssid',$bssid)->first();
    }

    public function getTeamsSwitches()
    {
        print "Cache::getTeamsSwitches()\n";
        if(!$this->teamsswitches)
        {
            $this->teamsswitches = TeamsSwitch::all();
        }
        return $this->teamsswitches;
    }

    public function getTeamsSwitch($chassisId)
    {
        return $this->getTeamsSwitches()->where('chassisId',$chassisId)->first();
    }

    public function getTeamsWaps()
    {
        print "Cache::getTeamsWaps()\n";
        if(!$this->teamswaps)
        {
            $this->teamswaps = TeamsWap::all();
        }
        return $this->teamswaps;
    }

    public function getTeamsWap($bssid)
    {
        print "Cache::getTeamsWap()\n";
        return $this->getTeamsWaps()->where('bssid',$bssid)->first();
    }

}