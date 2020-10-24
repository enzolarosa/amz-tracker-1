<?php

use App\Models\Team;
use App\Models\User;
use Illuminate\Database\Migrations\Migration;

class AddFirstTeamToDatabase extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $user = User::query()->where('email', 'enzo@vincenzolarosa.it')->first();
        $team = new Team([
            'name' => $user->first_name . ' Team\'s',
            'personal_team' => 0,
        ]);

        $team->user_id = $user->id;
        $team->save();
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        $user = User::query()->where('email', 'enzo@vincenzolarosa.it')->first();
        Team::query()->where([
            'name' => $user->first_name . ' Team\'s',
            'personal_team' => 0,
            'user_id' => $user->id,
        ])->delete();
    }
}
