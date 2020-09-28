<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\TeamsTrustedIp;
use App\PublicIp;

class CleanTeamsTrustedIps extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'whereuat:CleanTeamsTrustedIps';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Clean up unused TEAMS TRUSTED IPS.';

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
        $teamsTrustedIps = TeamsTrustedIp::all();
        $publicIps = PublicIp::all();

        foreach($teamsTrustedIps as $teamsTrustedIp)
        {
            print "Cleaning up TEAMS TRUSTED IP {$teamsTrustedIp->identity} {$teamsTrustedIp->description}...\n";
            $publicIp = $publicIps->where('real_ip', $teamsTrustedIp->identity)->first();
            if($publicIp)
            {
               print "PUBLIC IP exists on network, skipping!\n"; 
            } else {
                print "PUBLIC IP no longer exists on network, removing!\n";
                $teamsTrustedIp->delete();
            }
        }
    }

}
