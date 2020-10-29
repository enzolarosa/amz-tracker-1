<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Class CreateRequestLogsTable
 */
class CreateRequestLogsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('request_logs', function (Blueprint $table) {
            $table->bigIncrements('id');

            $table->string('provider');
            $table->uuid('request_id');

            $table->longText('request');
            $table->longText('response');

            $table->timestamps();
            $table->unique(['provider', 'request_id']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('request_logs');
    }
}
