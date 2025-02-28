<?php

namespace App\Console\Commands;

use App\Http\Controllers\CoachAnalyticsController;
use Illuminate\Console\Command;

class AnalyticsCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:analytics-command';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle()
    {
    
        $obj = new CoachAnalyticsController;
        $obj->index(true);
    }
}
