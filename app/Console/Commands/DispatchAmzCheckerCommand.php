<?php

namespace App\Console\Commands;

use App\Jobs\AmzChecker;
use App\Models\PriceTrace;
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
        PriceTrace::query()->where('enabled', true)->cursor()->each(function (PriceTrace $product) {
            $job = new AmzChecker();
            $job->setProduct($product);
            dispatch($job);
        });
    }
}
