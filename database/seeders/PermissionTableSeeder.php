<?php

namespace EscolaLms\Files\Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class PermissionTableSeeder extends Seeder
{
    public function run()
    {
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();
        Permission::findOrCreate('list:files', 'api');
        Permission::findOrCreate('upload:files', 'api');
        Permission::findOrCreate('move:files', 'api');
        Permission::findOrCreate('delete:files', 'api');

        $admin = Role::findOrCreate('admin', 'api');
        $tutor = Role::findOrCreate('tutor', 'api');

        $admin->givePermissionTo(['list:files', 'upload:files', 'move:files', 'delete:files']);
        $tutor->givePermissionTo(['list:files', 'upload:files', 'move:files', 'delete:files']);
    }
}
