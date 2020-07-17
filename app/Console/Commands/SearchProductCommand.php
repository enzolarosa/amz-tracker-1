<?php

namespace App\Console\Commands;

use App\Jobs\Amazon\SearchJob;
use Illuminate\Console\Command;

class SearchProductCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'amz:search {keyword}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Dispatch all amazon checker job';

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $keyword = $this->argument('keyword');
        $this->comment("I'll search $keyword product!");

        $job = new SearchJob($keyword);
        dispatch_now($job);
    }
}
