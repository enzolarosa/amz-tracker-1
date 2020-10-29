<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class CleanUpFailedJobs extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'cleanup:failed-jobs';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';


    /**
     * Execute the console command.
     *
     */
    public function handle()
    {
        DB::statement('delete from failed_jobs where date(failed_at) < date("' . now()->subDays(1) . '");');

        $this->comment("Done.");
    }
}
