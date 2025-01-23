<?php

namespace App\Console\Commands;

use App\Http\Controllers\ClientController;
use Illuminate\Console\Command;

class ClientCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:client-command';

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
        $obj = new ClientController;
        $obj->index($request, true);
    }
}
