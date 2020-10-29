<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAmzProductLogsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('amz_product_logs', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('amz_product_id');

            $table->jsonb('history');

            $table->timestamps();

            $table->foreign('amz_product_id')->on('amz_products')->references('id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('amz_product_logs');
    }
}
