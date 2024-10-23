<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;
use App\Models\Dhcp;

class GenerateScopesReport extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'whereuat:GenerateScopesReport';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generate Scopes report JSON file';

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
       $scopes = Dhcp::allWithSites();
       Storage::disk('public')->put('Scopes.json', response()->json($scopes)->getContent());
    }
}
