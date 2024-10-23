<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\DeviceSwitch;
use App\Models\E911Switch;


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

    public $DeviceSwitches;
    public $E911Switches;

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
        $this->deleteE911Switches();
        $this->addE911Switches();
        $this->updateE911Switches();
    }

    public function getDeviceSwitches()
    {
        if(!$this->DeviceSwitches)
        {
            $this->DeviceSwitches = DeviceSwitch::all();
        }
        return $this->DeviceSwitches;
    }

    public function getE911Switches()
    {
        $this->E911Switches = E911Switch::all();
        return $this->E911Switches;
    }

    public function addE911Switches()
    {
        print "*** ADDING E911 SWITCHES ***\n";
        $switches = $this->getDeviceSwitches();

        foreach($switches as $switch)
        {
            print "*** DEVICE SWITCH {$switch->name} ***\n";
            $e911switch = $switch->getE911Switch();
            if($e911switch)
            {
                print "E911 SWITCH already exists!  skip!\n";
                continue;
            }
            print "E911 SWITCH doesn't exist, adding!\n";
            $switch->addE911Switch();
        }
    }
  
    public function deleteE911Switches()
    {
        print "*** DELETING E911 SWITCHES ***\n";
        $e911switches = $this->getE911Switches();
        foreach($e911switches as $e911switch)
        {
            print "*** E911 SWITCH {$e911switch->description} with IP {$e911switch->switch_ip} ***\n";
            if(!$e911switch->getDeviceSwitch())
            {
                print "DEVICE SWITCH doesn't exist, deleting E911 SWITCH!\n";
                $e911switch->delete();
            }
            print "DEVICE SWITCH exists, skipping!\n";
        }
    }

    public function updateE911Switches()
    {
        print "*** UPDATING E911 SWITCHES ***\n";
        $switches = $this->getDeviceSwitches();

        foreach($switches as $switch)
        {
            print "*** DEVICE SWITCH {$switch->name} ***\n";
            if($switch->validateE911Switch())
            {
                print "E911 SWITCH is validated, skipping!\n";
                continue;
            }
            print "E911 SWITCH is NOT validated, updating E911 SWITCH!\n";
            $switch->updateE911Switch();
        }
    }

}
