<?php

namespace App\Console\Commands;

use App\Models\Setting;
use Exception;
use Illuminate\Console\Command;

class CleanUpSettingCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'setting:clear';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Cleanup the settings table';

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
     * @throws Exception
     */
    public function handle()
    {
        return Setting::query()->where('expire_at', '<', now())->delete();
    }
}
