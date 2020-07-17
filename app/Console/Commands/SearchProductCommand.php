<?php

namespace App\Console\Commands;

use App\Jobs\Amazon\SearchJob;
use App\Models\User;
use Illuminate\Console\Command;

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
     */
    public function handle()
    {
        $keyword = $this->argument('keyword');
        $this->comment("I'll search $keyword product!");
        $user = $this->option('user');

        $job = new SearchJob($keyword);
        if ($user) {
            $job->setUser(User::findOrFail($user));
        }
        dispatch_now($job);
    }
}
