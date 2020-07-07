<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAmzProductsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('amz_products', function (Blueprint $table) {
            $table->id();

            $table->string('asin')->unique();
            $table->string('title')->nullable();
            $table->longText('description')->nullable();
            $table->longText('featureDescription')->nullable();
            $table->string('author')->nullable();
            $table->string('stars')->nullable();
            $table->string('review')->nullable();
            $table->jsonb('images')->nullable();
            $table->string('currency')->nullable();
            $table->string('itemDetailUrl')->nullable();
            $table->jsonb('sellers')->nullable();

            $table->decimal('start_price', 10, 4)->nullable();
            $table->decimal('preview_price', 10, 4)->nullable();
            $table->decimal('current_price', 10, 4)->nullable();

            $table->boolean('enabled')->default(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('amz_products');
    }
}
