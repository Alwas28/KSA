<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class UserRoleSeeder extends Seeder
{
    public function run(): void
    {
        $userRoles = [
            ['user_id' => 1, 'role_id' => 2], // Alwas Muis -> Admin
            ['user_id' => 2, 'role_id' => 3], // Zahir -> Anggota
        ];

        foreach ($userRoles as $userRole) {
            DB::table('user_roles')->updateOrInsert(
                ['user_id' => $userRole['user_id'], 'role_id' => $userRole['role_id']],
                $userRole
            );
        }
    }
}
