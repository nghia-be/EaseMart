<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SettingsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

        $uri = [
            [
                'name'=>'Home',
                'vi_name' => '',
                'uri' =>'home',
                'status' =>'1'
            ],
            [
                'name'=>'Group Module',
                'vi_name' => '',
                'uri' =>'groupmodule',
                'status' =>'1'
            ],
            [
                'name'=>'Module',
                'vi_name' => '',
                'uri' =>'modules',
                'status' =>'1'
            ],
            [
                'name'=>'Group User',
                'vi_name' => '',
                'uri' =>'groups',
                'status' =>'1'
            ],
            [
                'name'=>'Role',
                'vi_name' => '',
                'uri' =>'roles',
                'status' =>'1'
            ],
            [
                'name'=>'User',
                'vi_name' => '',
                'uri' =>'user',
                'status' =>'1'
            ],
            [
                'name'=>'Manage OTP',
                'vi_name' => '',
                'uri' =>'manageotp',
                'status' =>'1'
            ],
            [
                'name'=>'Close Request',
                'vi_name' => '',
                'uri' =>'closehelprequest',
                'status' =>'1'
            ],
            [
                'name'=>'Log Activities',
                'vi_name' => '',
                'uri' =>'logactivities',
                'status' =>'1'
            ],
            [
                'name'=>'Setting',
                'vi_name' => '',
                'uri' =>'settings',
                'status' =>'1'
            ],
        ];
        DB::table('settings')->insert([
            'name' => 'uri_config',
            'value' => json_encode($uri)
        ]);
    }
}
