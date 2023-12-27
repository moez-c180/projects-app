<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Role;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Role::firstOrCreate([
            'id' => 777
        ], [
            'guard_name' => 'web',
            'name' => Role::SUPER_ADMIN,
        ]);
        
        Role::updateOrCreate([
            'id' => 999
        ],[
            'guard_name' => 'web',
            'name' => Role::ROOT_ADMIN,
        ]);
    }
}
