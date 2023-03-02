<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\TMS;
use App\E911Erl;

class CleanElins extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'whereuat:CleanElins';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Cleanup any ELINs that do not have an active ERL.';

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
        $this->deleteElins();
    }

    public function getElinsToDelete()
    {
        $delelins = [];
        $tms = TMS::create();
        $elins = $tms->getUsedCaElins();
        foreach($elins as $elin)
        {
            //print_r($elin);
            $erl = E911Erl::getByName($elin['name']);
            if(!$erl)
            {
                $delelins[] = $elin;
            }
        }
        return $delelins;
    }
   
    public function deleteElins()
    {
        $delelins = $this->getElinsToDelete();
        $tms = TMS::create();
        foreach($delelins as $elin)
        {
            $tms->releaseCaElin($elin['id']);
        }
    }

}
