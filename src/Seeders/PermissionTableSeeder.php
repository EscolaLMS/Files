<?php

namespace EscolaLms\Files\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;

class PermissionTableSeeder extends Seeder
{
    public function run()
    {
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();
        Permission::create(['name'=>'list:files']);
        Permission::create(['name'=>'edit:files']);
        Permission::create(['name'=>'move:files']);
        Permission::create(['name'=>'delete:files']);
    }
}
