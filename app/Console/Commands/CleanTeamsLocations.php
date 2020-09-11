<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Room;
use App\Cache;

class CleanTeamsLocations extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'whereuat:CleanTeamsLocations';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Clean up unused TEAMS LOCATIONS.';

    public $cache;

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->cache = new Cache;
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $this->cleanTeamsLocations();
    }

    public function cleanTeamsLocations()
    {
        print "Cleaning up un-used TEAMS LOCATIONS...\n";
        $locations = $this->cache->getTeamsLocations();
        foreach($locations as $location)
        {
            print "**********************************************\n";
            print "Checking TEAMS LOCATION ID {$location->locationId}...\n";
            if(!$location->location)
            {
                print "Default location is not editable! Skipping...\n";
                continue;
            }
            $room = Room::where('teams_location_id',$location->locationId)->first();
            if($room)
            {
                print "Found ROOM ID {$room->id}... skipping!\n";
                continue;
            } else {
                print "No ROOM found, removing TEAMS LOCATION ID {$location->locationId}...\n";
                try{
                    $location->delete();
                } catch(\Exception $e) {
                    print $e->getMessage();
                    continue;
                }
            }
        }
    }
}
