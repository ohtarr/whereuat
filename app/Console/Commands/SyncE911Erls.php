<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Room;
use App\E911Erl;
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

    public $rooms;

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
        $this->deleteE911Erls();
        $this->addE911Erls();
        $this->updateE911Erls();
    }

    public function getRooms()
    {
        if(!$this->rooms)
        {
            $this->rooms = Room::all();
        }
        return $this->rooms;
    }

    public function E911ErlsToAdd()
    {
        $rooms = $this->getRooms();

        //ADD ERLS
        foreach($rooms as $room)
        {
            $add = [];
            $erl = $room->getE911Erl();
            if(!$erl)
            {
                $add[] = $room;
            }
            return $add;
        }
    }

    public function addE911Erls()
    {
        print "*** ADDING E911 ERLS ***\n";
        $addrooms = $this->E911ErlsToAdd();

        //ADD ERLS
        foreach($addrooms as $room)
        {
            print "******************************************************************************\n";
            print "*** ROOM ID: {$room->id} ROOM NAME: {$room->name}***\n";
            print "******************************************************************************\n";
            $erl = $room->getE911Erl();
            if($erl)
            {
                print "*** E911 ERL ID {$erl->location_id} with name {$erl->erl_id} already found, skipping! ***\n";
                continue;
            }
            print "*** E911 ERL not found, creating E911 ERL with name {$room->generateErlName()}! ***\n";
            try
            {
                $room->addE911Erl();
            } catch (\Exception $e) {
                print $e->getMessage() . "\n";
            }

        }
    }

    public function E911ErlsToRemove()
    {
        $erls = E911Erl::all();

        foreach($erls as $erl)
        {
            $room = $erl->getRoom();
            if(!$room)
            {
                $delete[] = $erl;
            }
        }
        print "ERLs TO DELETE: " . count($delete) . "\n";
        //print_r($delete);
        foreach($delete as $del)
        {
            print $del->erl_id . "\n";
        }
        return $delete;
    }

    public function deleteE911Erls()
    {
        print "*** DELETING E911 ERLS ***\n";
        $delete = $this->E911ErlsToRemove();
        foreach($delete as $delerl)
        {
            print "deleting erl : " . $delerl->erl_id . " with name " . $delerl->erl_id . "...\n";
            try{
                $delerl->purge();
            } catch (\Exception $e) {
                print $e->getMessage() . "\n";
            }
        }

    }

    public function updateE911Erls()
    {
        print "*** UPDATING E911 ERLS ***\n";
        $rooms = $this->getRooms();

        //ADD ERLS
        foreach($rooms as $room)
        {
            print "*** ROOM with ID {$room->id} ***\n";
            if(!$room->validateE911Erl())
            {
                print "*** E911 ERL unable to be validated, deleting E911 switches and E911 ERL and recreating E911 ERL... ***\n";
                $erl = $room->getE911Erl();
                if($erl)
                {
                    print "*** deleting E911 switches ***\n";
                    $erl->deleteAllE911Switches();
                    print "*** deleting E911 ERL ***\n";
                    $room->deleteE911Erl();
                }
                print "*** re-adding E911 ERL ***\n";
                try
                {
                    $room->addE911Erl();
                } catch (\Exception $e) {
                    print $e->getMessage() . "\n";
                }
            } else {
                print "*** E911 ERL was validated, no need to update... ***\n";
            }
        }
    }
  
}
