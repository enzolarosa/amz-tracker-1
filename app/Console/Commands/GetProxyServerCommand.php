<?php

namespace App\Console\Commands;

use App\Models\ProxyServer;
use Illuminate\Console\Command;

class GetProxyServerCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'amz:proxy-server';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

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

        #TODO need to complete this command
        return 0;
    }
}
