<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePriceTraceLogsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('price_trace_logs', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('price_trace_id');

            $table->decimal('price', 10, 4)->nullable();
            $table->timestamps();

            $table->foreign('price_trace_id')->references('id')->on('price_trace');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('price_trace_logs');
    }
}
