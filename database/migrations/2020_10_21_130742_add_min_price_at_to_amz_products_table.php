<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddMinPriceAtToAmzProductsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('amz_products', function (Blueprint $table) {
            $table->decimal('min_price')->nullable()->after('enabled');
            $table->dateTimeTz('min_price_at')->nullable()->after('min_price');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('amz_products', function (Blueprint $table) {
            $table->dropColumn(['min_price', 'min_price_at']);
        });
    }
}
