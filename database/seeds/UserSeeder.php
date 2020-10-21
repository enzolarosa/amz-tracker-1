<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $user = User::query()->firstOrCreate([
            'email' => 'enzo@vincenzolarosa.it',
        ]);
        $user->password = bcrypt('123456789');
        $user->email_verified_at = now();
        $user->save();
    }
}
