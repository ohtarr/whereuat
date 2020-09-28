<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;
use App\TeamsWap;
use App\Bssid;

class SyncBssids extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'whereuat:SyncBssids';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Synchronize BSSIDS to TEAMS WAPs';

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
     * @return int
     */
    public function handle()
    {
        $this->syncTeamsWaps();
    }

    public function syncTeamsWaps()
    {
        $teamsWaps = new TeamsWap;
        $bssids = new Bssid;
        $msg = "********************* BEGIN " . get_class() . " *****************************\n";
        print $msg;
        Log::info($msg);

        foreach($bssids->cacheAll() as $bssid)
        {
            $msg = "BSSID {$bssid->bssid} - Syncing BSSID ID {$bssid->bssid}...\n";
            print $msg;
            Log::info($msg);

            $teamsWap = $teamsWaps->cacheFind($bssid->bssid);
            $room = $bssid->getRoom();

            if($teamsWap)
            {
                $msg = "BSSID {$bssid->bssid} - TEAMS WAP Exists...\n";
                print $msg;
                Log::info($msg);
                if(!$bssid->validateTeamsWap($teamsWap))
                {
                    $msg = "BSSID {$bssid->bssid} - BSSID and TEAMS WAP do not match...\n";
                    print $msg;
                    Log::info($msg);
                    try{
                        $bssid->createOrUpdateTeamsBssid();
                    } catch(\Exception $e) {
                        $msg = $e->getMessage() ."\n";
                        print $msg;
                        Log::info($msg);
                    }
                }

            } else {
                $msg = "BSSID {$bssid->bssid} - TEAM WAP does not exist, creating...\n";
                print $msg;
                Log::info($msg);
                try{
                    $bssid->createOrUpdateTeamsBssid();
                } catch(\Exception $e) {
                    $msg = $e->getMessage() . "\n";
                    print $msg;
                    Log::info($msg);
                }
            }
        }
        $msg = "********************* END " . get_class() . " *********************\n";
        print $msg;
        Log::info($msg);
    }
  
}
