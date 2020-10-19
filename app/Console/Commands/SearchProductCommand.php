<?php

namespace App\Console\Commands;

use App\Jobs\Amazon\SearchJob;
use App\Models\User;
use Illuminate\Bus\Batch;
use Illuminate\Console\Command;
use Bus;
use Throwable;

class SearchProductCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'amz:search {keyword} {--user=}';

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
     * @throws Throwable
     */
    public function handle()
    {
        $keyword = $this->argument('keyword');
        $this->comment("I'll search $keyword product!");
        $user = $this->option('user');

        $searchJob = new SearchJob($keyword);
        if ($user) {
            $searchJob->setUser(User::findOrFail($user));
        }

        $batch = Bus::batch([
            $searchJob
        ])->onQueue('amz-search')->name("Searching `$keyword` products")->dispatch();

    }
}
