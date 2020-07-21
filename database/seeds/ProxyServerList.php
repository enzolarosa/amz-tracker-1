<?php

use App\Models\ProxyServer;
use Illuminate\Database\Seeder;

class ProxyServerList extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        ProxyServer::query()->update(['active' => false]);

        collect([
            "114.226.163.68:9999",
            "144.76.70.92:55245",
            "13.53.198.203:20018",
            "149.90.31.59:1080",
            "192.162.124.158:1080",
            "90.55.211.7:1080",
            "181.5.202.190:1080",
            "144.76.70.92:1370",
            "167.99.87.173:1080",
        ])->each(function (string $proxy) {
            ProxyServer::query()->updateOrCreate(['proxy' => $proxy], ['active' => true]);
        });

    }
}
