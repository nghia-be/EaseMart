<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
class GroupSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('groups')->insert([
            'group_name' => 'DEV',
        ]);
        DB::table('groups')->insert([
            'group_name' => 'BA',
        ]);
        DB::table('groups')->insert([
            'group_name' => 'QC',
        ]);
    }
}
