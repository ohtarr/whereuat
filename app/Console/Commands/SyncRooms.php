<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Room;
//use App\Cache;
use App\TeamsCivic;
use App\TeamsLocation;

class SyncRooms extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'whereuat:SyncRooms';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create DEFAULT ROOMs and create/map-to TEAMS LOCATIONS.';

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
     * @return mixed
     */
    public function handle()
    {
        $this->syncAllRooms();

    }

/*     public function syncAllRooms()
    {
        print "Syncing All ROOMS\n";
        
        foreach(Room::all() as $room)
        {
            print "**********************************************\n";
            print "Syncing ROOM ID {$room->id} for SITE {$room->building->site->name}...\n";

            $civic = $this->cache->getTeamsCivic($room->getAddress()->teams_civic_id);
            if(!$civic)
            {
                $error = "Failed to obtain TEAMS CIVIC from ADDRESS... Skipping ROOM!\n";
                print $error;
                continue;
            }

            if($room->isDefaultRoomInDefaultBuilding())
            {
                print "ROOM ID {$room->id} is the DEFAULT ROOM in the DEFAULT BUILDING for site {$room->building->site->name}...\n";
                $defaultLocation = $this->cache->getTeamsDefaultLocation($civic->civicAddressId);
                if(!$defaultLocation)
                {
                    print "Unable to determine DEFAULT LOCATION for site {$room->building->site->name}... Skipping!\n";
                }

                if($room->teams_location_id == $defaultLocation->locationId)
                {
                    print "ROOM ID {$room->id} LOCATION is set to correct DEFAULT LOCATION for site {$room->building->site->name}...\n";
                    continue;
                } else {
                    print "ROOM ID {$room->id} location set to LOCATION ID {$defaultLocation->locationId}...\n";
                    $room->teams_location_id = $defaultLocation->locationId;
                    $room->save();
                    continue;
                }
            } else {
                if($room->teams_location_id)
                {
                    print "ROOM ID {$room->id} is already set to TEAMS LOCATION ID {$room->teams_location_id}...\n";
                    $location = $this->cache->getTeamsLocation($room->teams_location_id);
                    if($location)
                    {
                        if($location->civicAddressId == $civic->civicAddressId)
                        {
                            print "TEAMS LOCATION ID {$location->locationId} is VALID...\n";
                            continue;
                        } else {
                            print "TEAMS LOCATION ID {$room->teams_location_id} was NOT FOUND or INVALID!  Creating new Location...\n";
                            $locationId = $room->createTeamsLocation();
                            if($locationId)
                            {
                                print "TEAMS LOCATION ID {$locationId} was created!\n";
                            } else {
                                print "FAILED to create TEAMS LOCATION!  Skipping!\n";
                                continue;
                            }
                        }
                    }
                } else {
                    print "ROOM does not have a TEAMS LOCATION ID assigned... Creating new TEAMS LOCATION...\n";
                    $locationId = $room->createTeamsLocation();
                    if($locationId)
                    {
                        print "TEAMS LOCATION ID {$locationId} was created!\n";
                    } else {
                        print "FAILED to create TEAMS LOCATION!  Skipping!\n";
                        continue;
                    }
                }

            }

            print "Completed Sync of ROOM ID {$room->id} for site {$room->building->site->name}...\n";
            print "**********************************************\n";
        }
    } */

    public function syncAllRooms()
    {
        print "Syncing All ROOMS\n";
        $teamsCivics = new TeamsCivic;
        $teamsLocations = new TeamsLocation;

        foreach(Room::all() as $room)
        {
            print "**********************************************\n";
            print "Syncing ROOM ID {$room->id} for SITE {$room->building->site->name}...\n";

            //$civic = $this->cache->getTeamsCivic($room->getAddress()->teams_civic_id);
            $civic = $teamsCivics->cacheFind($room->getAddress()->teams_civic_id);
            if(!$civic)
            {
                $error = "Failed to obtain TEAMS CIVIC from ADDRESS... Skipping ROOM!\n";
                print $error;
                continue;
            }
           
            if($room->teams_location_id)
            {
                print "ROOM ID {$room->id} is already set to TEAMS LOCATION ID {$room->teams_location_id}...\n";
                //$location = $this->cache->getTeamsLocation($room->teams_location_id);
                $location = $teamsLocations->cacheFind($room->teams_location_id);
                if($location)
                {
                    if($location->civicAddressId == $civic->civicAddressId)
                    {
                        print "TEAMS LOCATION ID {$location->locationId} is VALID...\n";
                        continue;
                    } else {
                        print "TEAMS LOCATION ID {$room->teams_location_id} was NOT FOUND or INVALID!  Creating new Location...\n";
                        $locationId = $room->createTeamsLocation();
                        if($locationId)
                        {
                            print "TEAMS LOCATION ID {$locationId} was created!\n";
                        } else {
                            print "FAILED to create TEAMS LOCATION!  Skipping!\n";
                            continue;
                        }
                    }
                }
            } else {
                print "ROOM does not have a TEAMS LOCATION ID assigned... Creating new TEAMS LOCATION...\n";
                $locationId = $room->createTeamsLocation();
                if($locationId)
                {
                    print "TEAMS LOCATION ID {$locationId} was created!\n";
                } else {
                    print "FAILED to create TEAMS LOCATION!  Skipping!\n";
                    continue;
                }
            }

            print "Completed Sync of ROOM ID {$room->id} for site {$room->building->site->name}...\n";
            print "**********************************************\n";
        }
    }
}
