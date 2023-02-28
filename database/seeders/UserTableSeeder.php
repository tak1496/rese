<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;

class UserTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $param = [
            'name' => '管理者',
            'email' => 'admin@example.com',
            'password' => bcrypt('password'),
            'auth_id' => 0,
            'auth' => 'admin',
            'shop_id' => null
        ];
        User::create($param);
    }
}
