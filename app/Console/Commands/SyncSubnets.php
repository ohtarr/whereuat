<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Dhcp;
use App\Site;
use App\TeamsSubnet;
use App\TeamsLocation;

class SyncSubnets extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'whereuat:SyncSubnets';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Synchronize all dhcp scopes to Teams Subnets';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $scopes = new Dhcp;
        $sites = Site::all();
        $teamsSubnets = new TeamsSubnet;
        $teamsLocations = new TeamsLocation;

        foreach($scopes->cacheAll() as $scope)
        {
            print 'Processing DHCP Scope with name: "' . $scope->name . '"' . "\n";
            $sitematch = 0;
            foreach($sites as $site)
            {
                if(stripos($scope->name,$site->name) !== false)
                {
                    $sitematch = $site;
                    break;
                }
            }
            if($sitematch)
            {
                print "Found a matching site: {$sitematch->name}.\n";
                $teamsSubnet = $teamsSubnets->cacheAll()->where('subnet',$scope->scopeID)->first();
                if($teamsSubnet)
                {
                    print "Found an existing Teams Subnet!  Checking TEAMS LOCATION...\n";
                    $teamsLocation = $teamsLocations->cacheAll()->where('locationId',$teamsSubnet->locationId)->first();
                    if($teamsLocation)
                    {
                        print "TEAMS LOCATION found and valid... skipping...\n";
                    } else {
                        print "No valid TEAMS LOCATION found!  Creating/Updating TEAMS SUBNET.\n";
                        try{
                            $teamsSubnet = $scope->createTeamsSubnet();
                        } catch(\Exception $e) {
                            print $e->getMessage();
                            continue;
                        }     
                    }
                } else {
                    print "No Teams Subnet found!  Adding...\n";
                    try{
                        $teamsSubnet = $scope->createTeamsSubnet();
                    } catch(\Exception $e) {
                        print $e->getMessage();
                        continue;
                    }     
                }
            }

        }

    }
}
