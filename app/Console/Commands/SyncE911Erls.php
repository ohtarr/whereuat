<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Room;
use App\DeviceErl;
use App\TMS;

class SyncE911Erls extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'whereuat:SyncE911Erls';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Synchronize ERLs to E911 gateways';

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
        $this->addE911Erls();
    }

    public function addE911Erls()
    {
        $erls = E911Erl::all();
        $rooms = Room::all();

        //ADD ERLS
        foreach($rooms as $room)
        {
            $erl = $room->getE911Erl();
            if(!$erl)
            {
                $room->addE911Erl();
            }
            foreach($erls as &$erl)
            {
                
            }
        }

        //DELETE ERLS
        foreach($erls as $erl)
        {
            //$room = Room::
        }
    }
  
}
