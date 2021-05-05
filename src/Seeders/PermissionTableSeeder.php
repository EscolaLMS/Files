<?php

namespace EscolaLms\Files\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;

class PermissionTableSeeder extends Seeder
{
    public function run()
    {
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();
        Permission::create(['name'=>'list:files', 'guard_name'=>'web']);
        Permission::create(['name'=>'edit:files', 'guard_name'=>'web']);
        Permission::create(['name'=>'move:files', 'guard_name'=>'web']);
        Permission::create(['name'=>'delete:files', 'guard_name'=>'web']);
    }
}
