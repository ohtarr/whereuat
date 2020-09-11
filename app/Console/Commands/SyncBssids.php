<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\DeviceSwitch;
use App\TeamsSwitch;
use App\Cache;

class SyncBssids extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'whereuat:SyncBssids';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Synchronize BSSIDS to TEAMS WAPs';

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
     * @return int
     */
    public function handle()
    {
        $this->syncTeamsWaps();
    }

    public function syncTeamsWaps()
    {
        foreach($this->cache->getBssids() as $bssid)
        {
            print "**********************************************\n";
            print "Syncing Bssid {$bssid->bssid} ...\n";

            $teamsWap = $this->cache->getTeamsWap($bssid);
            $room = $bssid->getRoom();
            //$locationId = $room->teams_location_id;
            //$roomLocation = $this->cache->getTeamsLocation($locationId);

            if($teamsWap)
            {
                if(!$bssid->validateTeamsWap($teamsWap))
                {
                    print "BSSID and TEAMS WAP do not match...\n";
                    $bssid->createOrUpdateTeamsBssid();
                }

            } else {
                print "TEAM WAP does not exist, creating...\n";
                $bssid->createOrUpdateTeamsBssid();
            }
        }
    }
  
}
