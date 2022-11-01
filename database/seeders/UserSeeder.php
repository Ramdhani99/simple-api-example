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
        User::create([
            'name' => 'DANIE',
            'email' => 'admin@admin.com',
            'password' => bcrypt('111111'),
            'role_id' => 1,
        ]);
    }
}
