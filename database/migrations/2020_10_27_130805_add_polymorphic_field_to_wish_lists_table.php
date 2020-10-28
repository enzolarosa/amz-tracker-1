<?php

use App\Models\User;
use App\Models\WishList;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddPolymorphicFieldToWishListsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('wish_lists', function (Blueprint $table) {
            $table->string('trackable_id')->nullable()->after('id');
            $table->string('trackable_type')->nullable()->after('trackable_id');
        });

        /** @var WishList $record */
        foreach (WishList::query()->cursor() as $record) {
            $record->trackable_type = User::class;
            $record->trackable_id = $record->user_id;
            $record->save();
        }

        Schema::table('wish_lists', function (Blueprint $table) {
            $table->dropForeign(['user_id']);
            $table->dropColumn('user_id');
        });
    }
}
