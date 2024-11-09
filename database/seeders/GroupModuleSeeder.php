<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
class GroupModuleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        DB::table('group_module')->insert([
            'group_module_name' => 'ADMIN'
        ]);
        DB::table('group_module')->insert([
            'group_module_name' => 'REPORT'
        ]);
    }
}
