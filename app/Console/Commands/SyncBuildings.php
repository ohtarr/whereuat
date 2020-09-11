<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Building;

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
        print "Syncing All BUILDINGS\n";
        
        foreach(Building::all() as $building)
        {
            print "**********************************************\n";
            print "Syncing BUILDING ID {$building->id} for SITE {$building->site->name}...\n";

            $defaultRoom = $building->syncDefaultRoom();
            if(!$defaultRoom)
            {
                print "Unable to obtain/create DEFAULT ROOM for BUILDING ID {$building->id} at site {$building->site->name}, skipping site...\n";
                continue;
            }

            print "Completed Sync of BUILDING ID {$building->id} for SITE {$building->site->name}...\n";
            print "**********************************************\n";
        }
    }

}
