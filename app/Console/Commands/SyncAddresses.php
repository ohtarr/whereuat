<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;
use App\Address;
//use App\Cache;
use App\TeamsCivic;

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

        $this->SyncAllAddresses();
    }

    public function SyncAllAddresses()
    {
        $msg = "********************* BEGIN " . get_class() . " *****************************\n";
        print $msg;
        Log::info($msg);
        
        $addresses = Address::all();
        $civics = new TeamsCivic;
        $civics->cacheAll();

        foreach($addresses as $address)
        {
            $create = false;
            //SYNC ADDRESS = Create TEAMS CIVIC if missing.
            $msg = "ADDRESS {$address->id} - Syncing ADDRESS ID {$address->id}...\n";
            print $msg;
            Log::info($msg);
            if($address->teams_civic_id)
            {
                $msg =  "ADDRESS {$address->id} - Teams Civic: {$address->teams_civic_id} is already assigned...\n";
                print $msg;
                Log::info($msg);
                try{
                    //$civic = $this->cache->getTeamsCivic($address->teams_civic_id);
                    $civic = $civics->cacheFind($address->teams_civic_id);
                } catch(\Exception $e) {
                    $msg = $e->getMessage();
                    print $msg;
                    Log::info($msg);
                    continue;
                }
                if($civic)
                {
                    $msg = "ADDRESS {$address->id} - TEAMS CIVIC ID {$civic->civicAddressId} exists in Teams...\n";
                    print $msg;
                    Log::info($msg);
                    //CHECK ADDRESS
                    $match = $address->compareTeamsCivic($civic);
                    if(!$match)
                    {
                        $msg = "ADDRESS {$address->id} - ADDRESS and TEAMS CIVIC do NOT match!  Creating new TEAMS CIVIC\n";
                        print $msg;
                        Log::info($msg);
                        $create = true;
                    }
                } else {
                    $msg = "ADDRESS {$address->id} - Unable to find existing TEAMS CIVIC, creating new TEAMS CIVIC...\n";
                    print $msg;
                    Log::info($msg);
                    $create = true;
                }
                
            } else {
                $msg = "ADDRESS {$address->id} - Unable to find existing TEAMS CIVIC ID, creating a new TEAMS CIVIC...\n";
                print $msg;
                Log::info($msg);
                $create = true;
            }
            if($create == true)
            {
                try{
                    $civic = $address->createTeamsCivic();
                } catch(\Exception $e) {
                    $msg = $e->getMessage();
                    print $msg;
                    Log::info($msg);
                }
            }
            $msg = "ADDRESS {$address->id} - Completed Sync of ADDRESS ID {$address->id}...\n";
            print $msg;
            Log::info($msg);
        }
        $msg = "********************* END " . get_class() . " *********************\n";
        print $msg;
        Log::info($msg);
    }

}
