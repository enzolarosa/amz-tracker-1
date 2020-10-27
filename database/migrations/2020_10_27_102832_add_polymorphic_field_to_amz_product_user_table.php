<?php

use App\Models\AmzProductUser;
use App\Models\User;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddPolymorphicFieldToAmzProductUserTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('amz_product_user', function (Blueprint $table) {
            $table->string('trackable_id')->nullable()->after('id');
            $table->string('trackable_type')->nullable()->after('trackable_id');
        });

        /** @var AmzProductUser $record */
        foreach (AmzProductUser::query()->cursor() as $record) {
            $record->trackable_type = User::class;
            $record->trackable_id = $record->user_id;
            $record->save();
        }

        Schema::table('amz_product_user', function (Blueprint $table) {
            $table->dropForeign(['user_id']);
            $table->dropColumn('user_id');
        });
    }
}
