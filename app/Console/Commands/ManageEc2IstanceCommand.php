<?php

namespace App\Console\Commands;

use App\Models\Setting;
use Illuminate\Console\Command;

class ManageEc2IstanceCommand extends Command
{
    protected int $maxInstances = 5;

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'aws:ec2-manage';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create or remove ec2 istances';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $instances = Setting::read('ec2_istances');
        if (is_null($instances)) {
            $instances = Setting::store('ec2_istances', json_encode([]));
        }
        $instances = collect($instances);
        $ec2 =\AWS::createClient('ec2');
        dd($ec2);
        $current = 0;
        $instances = $instances->reject(function ($instance) {
            $expired = false;
            if ($expired) {


            }
            return $expired;
        });
        for ($i = $instances->count(); $i <= $this->maxInstances; $i++) {
            $ec2 = (object)[

            ];

            $instances->push($ec2);
        }

        Setting::store('ec2_istances', $instances->toJson());
    }
}
