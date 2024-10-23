<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\TeamsSwitch;
use App\Models\DeviceSwitch;

class CleanTeamsSwitches extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'whereuat:CleanTeamsSwitches';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Clean up unused TEAMS SWITCHES.';

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
        $teamsSwitches = new TeamsSwitch;
        $switches = DeviceSwitch::all();

        foreach($teamsSwitches->cacheAll() as $teamsSwitch)
        {
            $switch = $switches->where('mac', $teamsSwitch->chassisId)->first();
            if($switch)
            {
               print "Switch exists on network, skipping!\n"; 
            } else {
                print "Switch no longer exists on network, removing!\n";
                $teamsSwitch->delete();
            }
        }
    }

}
