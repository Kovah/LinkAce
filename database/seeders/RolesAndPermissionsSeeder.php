<?php

namespace Database\Seeders;

use App\Enums\Permission as PermissionEnum;
use App\Enums\Role as RoleEnum;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RolesAndPermissionsSeeder extends Seeder
{
    public function run()
    {
        /** @var Role $adminRole */
        $adminRole = Role::updateOrCreate(['name' => RoleEnum::ADMIN], ['name' => RoleEnum::ADMIN]);
        Role::updateOrCreate(['name' => RoleEnum::USER], ['name' => RoleEnum::USER]);

        $adminSysPerm = Permission::create(['name' => PermissionEnum::ADMIN_SYSTEM_SETTINGS]);
        $adminUserPerm = Permission::create(['name' => PermissionEnum::ADMIN_USER_MANAGEMENT]);
        $adminApiPerm = Permission::create(['name' => PermissionEnum::ADMIN_API_MANAGEMENT]);

        $adminRole->permissions()->sync([$adminSysPerm->id, $adminUserPerm->id, $adminApiPerm->id]);
    }
}
