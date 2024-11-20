<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;
use App\Models\Building;

class SyncBuildings extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'whereuat:SyncBuildings';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create DEFAULT ROOMs for each BUILDING...\n';

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
        $this->syncAllBuildings();
    }

    public function syncAllBuildings()
    {
        $msg = "********************* BEGIN " . get_class() . " *****************************\n";
        print $msg;
        Log::info($msg);
        foreach(Building::all() as $building)
        {
            unset($site);
            $site = $building->site;
            if(!$site)
            {
                $msg = get_class() . "::" . __FUNCTION__ . " - BUILDING {$building->id} - Site ID {$building->site_id} not found, purging building...\n";
                print $msg;
                Log::info($msg);                
                $building->purge();
                continue;
            }
            $msg = get_class() . "::" . __FUNCTION__ . " - BUILDING {$building->id} - Syncing BUILDING ID {$building->id} for SITE {$building->site->name}...\n";
            print $msg;
            Log::info($msg);

            $defaultRoom = $building->syncDefaultRoom();
            if(!$defaultRoom)
            {
                $msg = get_class() . "::" . __FUNCTION__ . " - BUILDING {$building->id} - Unable to obtain/create DEFAULT ROOM for BUILDING ID {$building->id} at site {$building->site->name}, skipping site...\n";
                print $msg;
                Log::info($msg);

                continue;
            }
            $msg = "BUILDING {$building->id} - Completed Sync of BUILDING ID {$building->id} for SITE {$building->site->name}...\n";
            print $msg;
            Log::info($msg);
        }
        $msg = "********************* END " . get_class() . " *********************\n";
        print $msg;
        Log::info($msg);
    }

}
