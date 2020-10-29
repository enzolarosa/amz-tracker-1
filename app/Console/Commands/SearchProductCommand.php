<?php

namespace App\Console\Commands;

use App\Models\SearchList;
use App\Models\User;
use Illuminate\Console\Command;
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

        if ($user) {
            $u = User::find($user);
            SearchList::query()->create([
                'keywords' => $keyword,
                'trackable_id' => $u->id,
                'trackable_type' => get_class($u),
            ]);
        }

        $this->call(UpdateSearchListCommand::class);
    }
}
