<?php

namespace App\Console\Commands;

use App\Jobs\Amazon\SearchJob;
use App\Models\SearchList;
use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Bus;
use Throwable;

class UpdateSearchListCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'amz:update-search';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Update the product from a search keywords';

    /**
     * Execute the console command.
     *
     * @return void
     * @throws Throwable
     */
    public function handle()
    {
        $searchList = SearchList::query()->where('enabled', 1);
        $this->comment("I've {$searchList->count()} search keywords to analyze!");

        $bar = $this->output->createProgressBar($count = $searchList->count());
        $bar->start();

        if ($count > 0) {
            $batch = Bus::batch([])->onQueue('check-amz-product')
                ->name("[" . now()->format('d M h:i') . "] UpdateSearchListCommand running")
                ->dispatch();

            $searchList->each(function (SearchList $list) use ($bar, $batch) {
                $list->touch();

                $searchJob = new SearchJob($list->keywords);
                $searchJob->setUser(User::findOrFail($list->user_id));

                $batch->add([$searchJob]);
                $bar->advance();
            });
        }

        $bar->finish();
        $this->comment("\nDone!");
    }
}
