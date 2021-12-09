<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Laravel\Horizon\Repositories\RedisWorkloadRepository;

class HorizonWorkload extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'horizon:workload';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Horizon workload information';

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $wlRepo = app(RedisWorkloadRepository::class);
        $workload = collect($wlRepo->get())->sortBy('name')->values()->toArray();
        $this->info(__(
            'Horizon Workload at :time',
            ['time' => now()->format('H:i:s')]
        ));
        $this->table(array_keys($workload[0]), $workload);
    }
}
