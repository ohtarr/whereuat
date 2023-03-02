<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\ServiceNowLocation;
use App\Address;
use App\Site;

class CleanSites extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'whereuat:CleanSites';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Cleanup any sites that have been demobilized in ServiceNow.';

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
        //$this->SitesToRemove();
        $this->RemoveDemobedSites();
    }

    public function SitesToRemove()
    {
        $sites = Site::all();
        $snowlocs = ServiceNowLocation::where('u_network_demob_date',"")->get();

        foreach($sites as $site)
        {
            foreach($snowlocs as $snowloc)
            {
                $match = 0;
                if($site->name == $snowloc->name)
                {
                    $match = 1;
                    break;
                }
            }
            if($match == 0)
            {
                $delete[] = $site;
            }
        }
        print "SITES TO DELETE: " . count($delete) . "\n";
        //print_r($delete);
        foreach($delete as $del)
        {
            print $del->name . "\n";
        }
        return $delete;
    }

    public function RemoveDemobedSites()
    {
        $delete = $this->SitesToRemove();
        foreach($delete as $delsite)
        {
            print "deleting site : " . $delsite->name . "\n";
            try{
                $delsite->purge();
            } catch (\Exception $e) {
                print $e->getMessage() . "\n";
            }
        }

    }
}
