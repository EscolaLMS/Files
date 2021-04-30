<?php

namespace EscolaLms\Files\Tests;

use EscolaLms\Files\EscolaLmsFilesServiceProvider;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Orchestra\Testbench\Factories\UserFactory;
use Spatie\Permission\Models\Permission;

class TestCase extends \EscolaLms\Core\Tests\TestCase
{
    protected function setUp(): void
    {
        parent::setUp();
        Storage::fake();

        Permission::create(['name'=>'list:files']);
        Permission::create(['name'=>'edit:files']);
        Permission::create(['name'=>'move:files']);
        Permission::create(['name'=>'delete:files']);

        $user = UserFactory::new()->create();
        $user->givePermissionTo(
            "list:files",
            "edit:files",
            "move:files",
            "delete:files",
        );
        Auth::setUser($user);
    }

    protected function getPackageProviders($app): array
    {
        return [EscolaLmsFilesServiceProvider::class];
    }
}
