<?php

use App\Models\Notification;
use App\Models\User;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddPolymorphicFieldToNotificationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('notifications', function (Blueprint $table) {
            $table->string('notificable_id')->nullable()->after('id');
            $table->string('notificable_type')->nullable()->after('notificable_id');
        });

        /** @var Notification $record */
        foreach (Notification::query()->cursor() as $record) {
            $record->notificable_type = User::class;
            $record->notificable_id = $record->user_id;
            $record->save();
        }

        Schema::table('notifications', function (Blueprint $table) {
            $table->dropForeign(['user_id']);
            $table->dropColumn('user_id');
        });
    }
}
