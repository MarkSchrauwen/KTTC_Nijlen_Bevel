<?php

namespace Database\Seeders;

use App\Models\Role;
use Illuminate\Database\Seeder;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Role::create(['name'=>'User']);
        Role::create(['name'=>'TeamCaptain']);
        Role::create(['name'=>'BlogModerator']);
        Role::create(['name'=>'ContentManager']);
        Role::create(['name'=>'SiteAdmin']);
        Role::create(['name'=>'Member']);
    }
}
