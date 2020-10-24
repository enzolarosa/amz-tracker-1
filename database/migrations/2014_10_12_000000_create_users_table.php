<?php

use Database\Seeders\UserSeeder;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id();

            $table->string('first_name')->nullable();
            $table->string('last_name')->nullable();
            $table->string('username')->nullable();
            $table->string('language_code')->nullable();

            $table->string('email')->unique()->nullable();
            $table->string('tId')->unique()->nullable();

            $table->timestamp('email_verified_at')->nullable();
            $table->string('password')->nullable();

            $table->rememberToken();
            $table->timestamps();
        });

        $userSeed = new UserSeeder();
        $userSeed->run();
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('users');
    }
}
