<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Address;
use App\Cache;

class CleanTeamsCivics extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'whereuat:CleanTeamsCivics';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Clean up unused TEAMS CIVIC ADDRESSES.';

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
        $this->cleanTeamsCivics();
    }

    public function cleanTeamsCivics()
    {
        print "Cleaning up un-used TEAMS CIVICS...\n";
        $civics = $this->cache->getTeamsCivics();
        foreach($civics as $civic)
        {
            print "**********************************************\n";
            print "Checking TEAMS CIVIC ID {$civic->civicAddressId}...\n";

            $address = Address::where('teams_civic_id',$civic->civicAddressId)->first();
            if($address)
            {
                print "Address ID {$address->id} is linked to TEAMS CIVIC ID {$civic->CivicAddressId}... Skipping!\n";
                continue;
            }

            $locations = $this->cache->getTeamsNonDefaultLocations($civic->civicAddressId);
            if($locations->isNotEmpty())
            {
                print "CIVIC ADDRESS ID {$civic->civicAddressId} has non-default locations attached to it! Skipping...\n";
                continue;
            }

            print "CIVIC ADDRESS ID {$civic->civicAddressId} is unused, deleting...\n";
            try{
                $civic->delete();
            } catch(\Exception $e) {
                print $e->getMessage();
                continue;
            }
        }
    }
}
