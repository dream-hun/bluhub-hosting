<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class UsersTableSeeder extends Seeder
{
    public function run(): void
    {
        $users = [
            [
                'id' => 1,
                'name' => 'Jacques MBABAZI',
                'email' => 'mbabazijacques@gmail.com',
                'password' => bcrypt('password'),
                'remember_token' => null,
                'two_factor_code' => '',
            ],
            [
                'id' => 2,
                'name' => 'Jean Paul TURIKUMWE',
                'email' => 'jeanpaulturikumwe@gmail.com',
                'password' => bcrypt('password'),
                'remember_token' => null,
                'two_factor_code' => '',
            ],
        ];

        User::insert($users);
    }
}
