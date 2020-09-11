<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Address;
use App\Cache;

class SyncAddresses extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'whereuat:SyncAddresses';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Get all ADDRESSES and create/sync TEAMS CIVICS.';

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
        $this->SyncAllAddresses();
    }

    public function SyncAllAddresses()
    {
        print "Syncing All ADDRESSES\n";
        $addresses = Address::all();
        foreach($addresses as $address)
        {
            $create = false;
            //SYNC ADDRESS = Create TEAMS CIVIC if missing.
            print "**********************************************\n";
            print "Syncing ADDRESS ID {$address->id} for site {$address->getSite()->name}...\n";
            if($address->teams_civic_id)
            {
                print "Found teams_civic_id...\n";
                try{
                    $civic = $this->cache->getTeamsCivic($address->teams_civic_id);
                } catch(\Exception $e) {
                    print $e->getMessage();
                    continue;
                }
                if($civic)
                {
                    print "Found TEAMS CIVIC with ID {$civic->civicAddressId}...\n";
                    //CHECK ADDRESS
                    $match = $address->compareTeamsCivic($civic);
                    if(!$match)
                    {
                        print "ADDRESS and TEAMS CIVIC do NOT match!  Creating new TEAMS CIVIC\n";
                        $create = true;
                    }
                } else {
                    print "Unable to find existing TEAMS CIVIC, creating new TEAMS CIVIC...\n";
                    $create = true;
                }
                
            } else {
                print "Unable to find existing TEAMS CIVIC ID, creating a new TEAMS CIVIC...\n";
                $create = true;
            }
            if($create == true)
            {
                try{
                    $civic = $address->createTeamsCivic();
                } catch(\Exception $e) {
                    print $e->getMessage();
                    continue;
                }
            }
            print "Completed Sync of ADDRESS ID {$address->id} for site {$address->getSite()->name} ...\n";
            print "**********************************************\n";
        }
    }

/*     public function compareCivicWithServiceNowLocation($civic,$snowloc)
    {
        $match = true;
        if(strtoupper($civic->houseNumber) != strtoupper($snowloc->u_street_number))
        {
            $match = false;
        }
        if(strtoupper($civic->preDirectional) != strtoupper($snowloc->u_street_predirectional))
        {
            $match = false;
        }
        if(strtoupper($civic->streetName) != strtoupper($snowloc->u_street_name))
        {
            $match = false;
        }
        if(strtoupper($civic->streetSuffix) != strtoupper($snowloc->u_street_suffix))
        {
            $match = false;
        }
        if(strtoupper($civic->postDirectional) != strtoupper($snowloc->u_street_postdirectional))
        {
            $match = false;
        }
        if(strtoupper($civic->city) != strtoupper($snowloc->city))
        {
            $match = false;
        }
        if(strtoupper($civic->postalCode) != strtoupper($snowloc->zip))
        {
            $match = false;
        }
        return $match;
    } */


}
