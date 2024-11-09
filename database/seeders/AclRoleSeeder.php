<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
class AclRoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('acl_roles')->insert([
            'module_id' => '1',
            'role_id' => '1',
            'view' => true
        ]);
        DB::table('acl_roles')->insert([
            'module_id' => '2',
            'role_id' => '1',
            'view' => true
        ]);
        DB::table('acl_roles')->insert([
            'module_id' => '3',
            'role_id' => '1',
            'view' => true
        ]);
        DB::table('acl_roles')->insert([
            'module_id' => '4',
            'role_id' => '1',
            'view' => true
        ]);
        DB::table('acl_roles')->insert([
            'module_id' => '3',
            'role_id' => '3',
            'view' => true
        ]);
    }
}
