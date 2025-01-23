<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Http\Controllers\SessionController;

class SessionCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:session-command';

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
        $request = new \Illuminate\Http\Request();
        $obj = new SessionController;
        $obj->index($request, true);
        // $obj->getUncompletedSessions($request, true);
    }
}
