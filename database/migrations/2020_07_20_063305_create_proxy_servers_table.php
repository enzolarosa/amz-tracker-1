<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProxyServersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('proxy_servers', function (Blueprint $table) {
            $table->id();

            $table->string('proxy');
            $table->boolean('active')->default(true);

            $table->timestamps();

            $table->softDeletes();
        });

        $seed = new ProxyServerList();
        $seed->run();
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('proxy_servers');
    }
}
