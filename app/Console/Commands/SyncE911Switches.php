<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\DeviceSwitch;


class SyncE911Switches extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'whereuat:SyncE911Switches';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Synchronize switches to E911 gateways';

    //public $cache;
    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        //$this->cache = new Cache;
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $this->addE911Switches();
    }

    public function addE911Switches()
    {
        $switches = DeviceSwitch::all();
        $e911switches = E911Switch::all();

        foreach($switches as $switch)
        {
            $e911switch = $e911switches->where('switch_ip',$switch->ip)->first();
            if($e911switch)
            {
                continue;
            }
            $newe911switch = E911Switch::add($switch->ip, $switch->vendor, $erl, $name);
        }

    }
  
}
