<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\TeamsWap;
use App\Bssid;

class CleanTeamsWaps extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'whereuat:CleanTeamsWaps';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Clean up unused TEAMS WAPS.';

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
        $teamsWaps = new TeamsWap;
        $bssids = Bssid::all();

        foreach($teamsWaps->cacheAll() as $teamsWap)
        {
            print "Cleaning up WAP {$teamsWap->bssid}...\n";
            $bssid = $bssids->where('bssid', $teamsWap->bssid)->first();
            if($bssid)
            {
               print "WAP exists on network, skipping!\n"; 
            } else {
                print "WAP no longer exists on network, removing!\n";
                $teamsWap->delete();
            }
        }
    }

}
