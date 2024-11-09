<?php

namespace Database\Seeders;

use App\Models\User;
use Faker\Factory;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        DB::table('users')->insert([
            'email' => 'admin@gmail.com',
            'name' =>" Admin",
            'password' =>'$2y$10$3IV5qf31AN7MlDsJtUBuW.0TkueM.2SZzMSN2TEBk1kUFG9yGa7Si', // admin,
            'role_id' => 1
        ]);
        DB::table('users')->insert([
            'email' => 'staff@gmail.com',
            'name' =>" Staff",
            'password' =>'$2y$10$KVJvvLLveyJW0U6tyqx75uZ4LP0TDZC0BGipO9yqBEv.D24GLkxfq', // admin,
            'role_id' => 3
        ]);
        // for($i = 0; $i<100;$i++){
        //     User::factory()->count(200)->create();
        // }
    }
}
