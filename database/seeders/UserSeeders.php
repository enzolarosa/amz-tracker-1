<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class UserSeeders extends Seeder
{
    public function run()
    {
        User::query()->create([
            'name' => 'Vincenzo',
            'email' => 'hello@enzolarosa.dev',
            'password' => bcrypt('secret'),
        ]);
    }
}
