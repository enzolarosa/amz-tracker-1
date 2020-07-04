<?php

namespace App\Console\Commands;

use App\Jobs\AmzChecker;
use App\Models\PriceTrace;
use Carbon\Carbon;
use Illuminate\Console\Command;

class DispatchAmzCheckerCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'amz:check';

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
        $pt = PriceTrace::query()
            ->where('enabled', true)
            ->where('updated_at', '<=', Carbon::now()->subHour())
            ->cursor();

        $this->comment("I've {$pt->count()} product to check!");

        $bar = $this->output->createProgressBar($pt->count());
        $bar->start();

        $pt->each(function (PriceTrace $product) use ($bar) {
            $job = new AmzChecker();
            $job->setProduct($product);
            dispatch($job);

            $bar->advance();
        });

        $bar->finish();
        $this->comment("\nDone!");
    }
}
