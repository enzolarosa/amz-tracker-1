<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class RedisCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'redis {params?}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Execute redis-cli command from laravel';

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $params = $this->argument('params') ?? 'flushall';
        $host = config('database.redis.default.host');

        $cmd = sprintf("redis-cli -h %s %s", $host, $params);

        exec($cmd, $results, $retVal);

        collect($results)->each(function ($result) use ($cmd, $retVal) {
            if ($retVal) {
                $result = "<error>$result</error>";
            }
            $this->comment(__('[:date][:command] :result', ['date' => now()->toDateTimeString(), 'command' => $cmd, 'result' => $result]));
        });
    }
}
