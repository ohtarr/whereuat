<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;
use App\Models\DeviceSwitch;
use App\Models\TeamsSwitch;
use App\Models\TeamsLocation;
//use App\Models\Cache;


class SyncSwitches extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'whereuat:SyncSwitches';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Synchronize switches to Teams Switches';

    //public $cache;
    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        //$this->cache = new Cache;
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $this->syncTeamsSwitches();
    }

    public function syncTeamsSwitches()
    {
        $teamsLocations = new TeamsLocation;
        $teamsSwitches = new TeamsSwitch;
        $switches = DeviceSwitch::all();

        foreach($switches as $switch)
        {
            print "**********************************************\n";
            print "Syncing Switch {$switch->name} ...\n";

            $room = $switch->getRoom();
            if(!$room)
            {
                print "Unable to locate ROOM for device... skppping!\n";
                continue;
            }

            //$location = $this->cache->getTeamsLocation($room->teams_location_id);
            $location = $teamsLocations->cacheFind($room->teams_location_id);
            if(!$location)
            {
                print "Unable to find TEAMS LOCATION... Skipping...\n";
                continue;
            }
            
            //$teamsSwitch = $this->cache->getTeamsSwitch($switch->mac);
            $teamsSwitch = $teamsSwitches->cacheFind($switch->mac);
            if(!$teamsSwitch)
            {
                print "SWITCH does not exist...CREATING TEAMS SWITCH!!\n";
                try
                {
                    $teamsSwitch = $switch->setTeamsSwitchFromTeamsLocationId($location->locationId);
                } catch(\Exception $e) {
                    $msg = "SYNCSWITCH SWITCH: {$switch->name} - " . $e->getMessage();
                    print $msg;
                    Log::error($msg);
                    continue;
                }

            } else {
                print "SWITCH already exists...Checking TEAMS LOCATION...\n";
                //$teamsSwitchLocation = $this->cache->getTeamsLocation($teamsSwitch->locationId);
                $teamsSwitchLocation = $teamsLocations->cacheFind($teamsSwitch->locationId);
                if($teamsSwitchLocation)
                {
                    print "TEAMS SWITCH location found....\n";
                    if($teamsSwitchLocation && ($teamsSwitchLocation->locationId != $location->locationId))
                    {
                        print "TEAMS SWITCH location is not correct, fixing...\n";
                        try
                        {
                            $teamsSwitch = $switch->setTeamsSwitchFromTeamsLocationId($location->locationId);
                        } catch(\Exception $e) {
                            $msg = "SYNCSWITCH SWITCH: {$switch->name} - " . $e->getMessage();
                            print $msg;
                            Log::error($msg);
                            continue;
                        }
                    } else {
                        print "TEAMS SWITCH location is set correct!\n";
                    }
                } else {
                    print "TEAMS SWITCH location not found, Assigning to ROOM existing location...\n";
                    try
                    {
                        $teamsSwitch = $switch->setTeamsSwitchFromTeamsLocationId($location->locationId);
                    } catch(\Exception $e) {
                        $msg = "SYNCSWITCH SWITCH: {$switch->name} - " . $e->getMessage();
                        print $msg;
                        Log::error($msg);
                        continue;
                    }
                }
            }
        }
    }
  
}
