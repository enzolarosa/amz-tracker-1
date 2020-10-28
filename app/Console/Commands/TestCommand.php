<?php

namespace App\Console\Commands;

use App\Models\AmzProductUser;
use App\Models\User;
use Illuminate\Console\Command;

class TestCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'amz:try';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Testing command';

    /**
     * Execute the console command.
     *
     */
    public function handle()
    {
        $user = User::find(1);

        $user->products->each(function (AmzProductUser $amzProductUser) {
            dd($amzProductUser->product->toArray());
        });

    }
}
