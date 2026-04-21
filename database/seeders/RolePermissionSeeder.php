<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class RolePermissionSeeder extends Seeder
{
    public function run(): void
    {
        // Semua permission (id 1-28) diberikan ke role Admin (id=2)
        $adminPermissionIds = [1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12, 13, 14, 15, 16, 17, 18, 19, 20, 21, 22, 23, 24, 25, 26, 27, 28];

        foreach ($adminPermissionIds as $permissionId) {
            DB::table('role_permissions')->updateOrInsert(
                ['role_id' => 2, 'permission_id' => $permissionId],
                ['role_id' => 2, 'permission_id' => $permissionId]
            );
        }
    }
}
