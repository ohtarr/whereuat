<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Address;
use App\Site;
use App\Building;
use App\Room;

class SyncSites extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'whereuat:SyncSites';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create DEFAULT BUILDING, DEFAULT ROOM, and CONTACT for each site.';

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
        $this->syncAllSites();

    }

    public function syncAllSites()
    {
        print "Syncing All SITES\n";
        
        foreach(Site::all() as $site)
        {
            //SYNC SNOWLOC = Create SITE if missing.
            print "**********************************************\n";
            print "Syncing Site {$site->name} with ID {$site->id}...\n";

            $address = $site->syncAddress();
            if(!$address)
            {
                print "Unable to sync ADDRESS for site {$site->name}, skipping site...\n";
                continue;                
            }

            $defaultBuilding = $site->syncDefaultBuilding();
            if(!$defaultBuilding)
            {
                print "Unable to obtain/create DEFAULT BUILDING for site {$site->name}, skipping site...\n";
                continue;
            }
            try{
                $contact = $site->syncContact();
            } catch(\Exception $e) {
                print $e->getMessage();
                //print "Unable to obtain CONTACT for site {$site->name}, skipping site...\n";
                continue;
            }            

            print "Completed Sync of SITE {$site->name} ...\n";
            print "**********************************************\n";
        }
    }

}
