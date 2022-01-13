<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\PublicIp;
use App\TeamsTrustedIp;
use Illuminate\Support\Facades\Log;

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
                $msg = "SYNCPUBLICIPS PUBLICIP : {$publicip->real_ip} does NOT exist, adding!";
                print $msg;
                Log::error($msg);
                try{
                    $trusted = new TeamsTrustedIp;
                    //$trusted->identity = $publicip->real_ip;
                    //$trusted->maskBits = "32";
                    $trusted->maskBits = $publicip->cidr;
                    $trusted->description = strtoupper(substr($publicip->device_name,0,8));
                    $trusted->ipAddress = $publicip->real_ip;
                    $trusted->save();
                } catch(\Exception $e) {
                    $msg = "SYNCPUBLICIPS PUBLICIP : {$publicip->real_ip} - " . $e->getMessage();
                    print $msg;
                    Log::error($msg);
                    continue;
                }
            } else {
                print "IP {$publicip->real_ip} already exists.  Checking if Description matches...\n";
                if(strtoupper(substr($publicip->device_name,0,8)) != $exists->description)
                {
                    $msg = "SYNCPUBLICIPS PUBLICIP : {$publicip->real_ip} Description does NOT match, updating...";
                    print $msg;
                    Log::error($msg);
                    try{
                        $exists->description = strtoupper(substr($publicip->device_name,0,8));
                        $exists->save();
                    } catch(\Exception $e) {
                        $msg = "SYNCPUBLICIPS PUBLICIP : {$publicip->real_ip} - " . $e->getMessage();
                        print $msg;
                        Log::error($msg);
                        continue;
                    }
                }
            }
        }
    }

}
