<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class FixPreviewFieldNameToAmzProductsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('amz_products', function (Blueprint $table) {
            $table->renameColumn('preview_price', 'previous_price');
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
            $table->renameColumn('previous_price', 'preview_price');
        });
    }
}
