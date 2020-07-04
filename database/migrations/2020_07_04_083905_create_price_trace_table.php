<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePriceTraceTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('price_trace', function (Blueprint $table) {
            $table->id();

            $table->string('name')->nullable();
            $table->string('product_id');
            $table->string('store')->default('IT');

            $table->decimal('first_price', 10, 4)->nullable();
            $table->decimal('latest_price', 10, 4)->nullable();
            $table->decimal('current_price', 10, 4)->nullable();

            $table->boolean('enabled')->default(true);
            $table->timestamps();

            $table->unique(['product_id', 'store']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('price_trace');
    }
}
