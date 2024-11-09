<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
class ModuleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('modules')->insert([
            'module_name' => 'Home',
            'uri' =>'',
            'icon' =>'fas fa-home',
        ]);
        DB::table('modules')->insert([
            'module_name' => 'Role',
            'uri' =>'roles',
            'icon' =>'fas fa-tachometer-alt',
            'group_module_id'=>1
        ]);
        DB::table('modules')->insert([
            'module_name' => 'User',
            'uri' =>'user',
            'icon' =>'fas fa-user',
            'group_module_id'=>2
        ]);
        DB::table('modules')->insert([
            'module_name' => 'Module',
            'uri' =>'modules',
            'icon' =>'fas fa-bars',
            'group_module_id'=>1
        ]);
        DB::table('modules')->insert([
            'module_name' => 'Groups',
            'uri' =>'groups',
            'icon' =>'fas fa-users',
            'group_module_id'=>1
        ]);
        DB::table('modules')->insert([
            'module_name' => 'Group Module',
            'uri' =>'groupmodule',
            'icon' =>'fa fa-cogs',
            'group_module_id'=>1
        ]);
        DB::table('modules')->insert([
            'module_name' => 'Log',
            'uri' =>'logactivities',
            'icon' =>'fa fa-cogs',
            'group_module_id'=>1
        ]);
    }
}
