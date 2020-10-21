<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\PublicIp;
use App\TeamsTrustedIp;

class SyncPublicIps extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'whereuat:SyncPublicIps';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Synchronize all known public IPs to Teams Trusted Ips';

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
        $publicips = PublicIp::all();
        $teamstrustedips = TeamsTrustedIp::all();

        foreach($publicips as $publicip)
        {
            unset($exists);

            print "Syncing IP {$publicip->real_ip} ...\n";

            $exists = $teamstrustedips->where('identity',$publicip->real_ip)->first();

            if(!$exists)
            {
                print "IP {$publicip->real_ip} does NOT exist, adding!\n";
                $trusted = new TeamsTrustedIp;
                //$trusted->identity = $publicip->real_ip;
                //$trusted->maskBits = "32";
                $trusted->maskBits = $publicip->cidr;
                $trusted->description = strtoupper(substr($publicip->device_name,0,8));
                $trusted->ipAddress = $publicip->real_ip;
                $trusted->save();
            } else {
                print "IP {$publicip->real_ip} already exists.  Checking if Description matches...\n";
                if(strtoupper(substr($publicip->device_name,0,8)) != $exists->description)
                {
                    print "Description does NOT match, updating...\n";
                    $exists->description = strtoupper(substr($publicip->device_name,0,8));
                    $exists->save();
                }
            }
        }
    }

}
