<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;
use App\Models\Address;
//use App\Models\Cache;
use App\Models\TeamsCivic;

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
            unset($civic);
            $create = false;
            //SYNC ADDRESS = Create TEAMS CIVIC if missing.
            $msg = "SYNCADDRESSES ADDRESS ID: {$address->id} - Syncing ADDRESS ID {$address->id}...\n";
            print $msg;
            Log::info($msg);
            if($address->teams_civic_id)
            {
                $msg =  "SYNCADDRESSES ADDRESS ID: {$address->id} - Teams Civic: {$address->teams_civic_id} is already assigned...\n";
                print $msg;
                Log::info($msg);
                try{
                    //$civic = $this->cache->getTeamsCivic($address->teams_civic_id);
                    $civic = $civics->cacheFind($address->teams_civic_id);
                } catch(\Exception $e) {
                    $msg = "SYNCADDRESSES ADDRESS ID: {$address->id} - " . $e->getMessage();
                    print $msg;
                    Log::error($msg);
                    continue;
                }
                if($civic)
                {
                    $msg = "SYNCADDRESSES ADDRESS ID: {$address->id} - TEAMS CIVIC ID {$civic->civicAddressId} exists in Teams...\n";
                    print $msg;
                    Log::info($msg);
                    //CHECK ADDRESS
                    $match = $address->compareTeamsCivic($civic);
                    if(!$match)
                    {
                        $msg = "SYNCADDRESSES ADDRESS ID: {$address->id} - ADDRESS and TEAMS CIVIC do NOT match!  Creating new TEAMS CIVIC\n";
                        print $msg;
                        Log::info($msg);
                        $create = true;
                    } else {
                        $msg = "SYNCADDRESSES ADDRESS ID: {$address->id} - ADDRESS and TEAMS CIVIC match!\n";
                        print $msg;
                        Log::info($msg);
                    }
                } else {
                    $msg = "SYNCADDRESSES ADDRESS ID: {$address->id} - Unable to find existing TEAMS CIVIC, creating new TEAMS CIVIC...\n";
                    print $msg;
                    Log::info($msg);
                    $create = true;
                }
                
            } else {
                $msg = "SYNCADDRESSES ADDRESS ID: {$address->id} - Unable to find an assigned TEAMS CIVIC ID, creating a new TEAMS CIVIC...\n";
                print $msg;
                Log::info($msg);
                $create = true;
            }
            if($create == true)
            {
                try{
                    $newcivic = $address->createTeamsCivic();
                } catch(\Exception $e) {
                    $msg = "SYNCADDRESSES ADDRESS ID {$address->id} - " . $e->getMessage() . "\n";
                    print $msg;
                    Log::error($msg);
                }
                if(isset($newcivic))
                {
                    $msg = "SYNCADDRESSES ADDRESS ID: {$address->id} - Created TEAMS CIVIC with ID of {$newcivic->civicAddressId}.\n";
                    print $msg;
                    Log::info($msg);
                } else {
                    $msg = "SYNCADDRESSES ADDRESS ID: {$address->id} - Failed to create TEAMS CIVIC!  Skipping ADDRESS...\n";
                    print $msg;
                    Log::error($msg);
                }
            }
            $msg = "SYNCADDRESSES ADDRESS ID: {$address->id} - Completed Sync of ADDRESS ID {$address->id}...\n";
            print $msg;
            Log::info($msg);
        }
        $msg = "********************* END " . get_class() . " *********************\n";
        print $msg;
        Log::info($msg);
    }

}
