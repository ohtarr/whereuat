<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class SyncAll extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'whereuat:SyncAll';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Run all Synchronizers...';

    public $cache;

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
        $this->call('whereuat:SyncServiceNowLocations');
        $this->call('whereuat:SyncSites');
        $this->call('whereuat:SyncAddresses');
        $this->call('whereuat:SyncBuildings');
        $this->call('whereuat:SyncRooms');

        $this->call('whereuat:SyncPublicIps');
        $this->call('whereuat:SyncSwitches');
    }


}
