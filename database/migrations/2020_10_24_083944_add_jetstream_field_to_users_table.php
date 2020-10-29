<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddJetstreamFieldToUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('name')->nullable()->after('batch_id');
            $table->foreignId('current_team_id')->nullable()->after('remember_token');
            $table->text('profile_photo_path')->nullable()->after('current_team_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['current_team_id', 'profile_photo_path', 'name']);
        });
    }
}
