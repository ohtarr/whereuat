<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\TeamsSubnet;
use App\Dhcp;

class CleanTeamsSubnets extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'whereuat:CleanTeamsSubnets';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Clean up unused TEAMS SUBNETS.';

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
     * @return mixed
     */
    public function handle()
    {
        $teamsSubnets = new TeamsSubnet;
        $dhcp = Dhcp::all();

        foreach($teamsSubnets->cacheAll() as $teamsSubnet)
        {
            print "Cleaning up Subnet {$teamsSubnet->subnet} {$teamsSubnet->description}...\n";
            $scope = $dhcp->where('scopeID', $teamsSubnet->subnet)->first();
            if($scope)
            {
               print "SUBNET exists on network, skipping!\n"; 
            } else {
                print "SUBNET no longer exists on network, removing!\n";
                $teamsSubnet->delete();
            }
        }
    }

}
