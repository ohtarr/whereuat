<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\ServiceNowLocation;
use App\Address;
use App\Site;
use App\TeamsCivic;
use App\TeamsLocation;

class SyncServiceNowLocations extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'whereuat:SyncServiceNowLocations';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Get all locations from Service-Now and create Sites and Addresses from them.';

    public $snowlocs;

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
        $this->syncAllServiceNowLocations();

    }

    public function getServiceNowLocations()
    {
       /*  $snowlocs = ServiceNowLocation::where('sys_id',"0520d0fddb3a3b40743afe213996194b")->get();
        $this->snowlocs = $snowlocs;
        return $this->snowlocs; */




        if(!$this->snowlocs)
        {
            $snowlocs = ServiceNowLocation::where('u_network_demob_date',"")->get();
            //$snowlocs = $snowlocs::whereNotNull('u_network_demob_date');
            $this->snowlocs = $snowlocs;
        }
        return $this->snowlocs;
    }


    public function syncAllServiceNowLocations()
    {
        $count = 0;
        $maxcount = 20;
        print "Syncing All Service Now Locations\n";
        
        foreach($this->getServiceNowLocations() as $snowloc)
        {
            $count++;
            if($count > $maxcount)
            {
                break;
            }
            //SYNC SNOWLOC = Create SITE if missing.
            print "**********************************************\n";
            print "Syncing Location " . $snowloc->name . "...\n";
            print "SNOW LOC SYS ID : " . $snowloc->sys_id . "\n";

            try{
                $site = $snowloc->syncSite();
            } catch(\Exception $e) {
                print $e;
                continue;
            }
/*             try{
                $address = $site->syncAddress();
            } catch(\Exception $e) {
                print $e;
                continue;
            } */

            print "Completed Sync of location {$snowloc->name} ...\n";
            print "**********************************************\n";

        }
    }

}
