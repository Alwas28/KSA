<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        $users = [
            [
                'id'                => 1,
                'name'              => 'Alwas Muis',
                'email'             => 'alwas.muis@umkendari.ac.id',
                'email_verified_at' => null,
                'password'          => Hash::make('password'),
                'remember_token'    => null,
                'created_at'        => '2025-12-12 12:24:32',
                'updated_at'        => '2025-12-12 12:24:32',
            ],
            [
                'id'                => 2,
                'name'              => 'Zahir',
                'email'             => 'zahir@umkendari.ac.id',
                'email_verified_at' => null,
                'password'          => Hash::make('password'),
                'remember_token'    => null,
                'created_at'        => '2025-12-13 21:34:20',
                'updated_at'        => '2025-12-13 21:34:20',
            ],
        ];

        foreach ($users as $user) {
            DB::table('users')->updateOrInsert(['id' => $user['id']], $user);
        }
    }
}
