<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class CleanAll extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'whereuat:CleanAll';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Run all Cleaners...';

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
        $this->call('whereuat:CleanTeamsLocations');
        $this->call('whereuat:CleanTeamsCivics');
    }


}
