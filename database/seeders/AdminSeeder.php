<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Role;

class AdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $user = User::updateOrCreate([
            'email' => 'abdelrahman.moez@gmail.com',
        ],[
            'name' => 'Abdelrahman Moez',
            'password' => bcrypt(env('ROOT_PASSWORD'))
        ]);
        $user->assignRole(Role::ROOT_ADMIN);
    }
}
