<?php

namespace EscolaLms\Files\Tests;

use EscolaLms\Core\Models\User;
use EscolaLms\Files\EscolaLmsFilesServiceProvider;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class TestCase extends \EscolaLms\Core\Tests\TestCase
{
    protected function setUp(): void
    {
        parent::setUp();
        Storage::fake();

        $user = User::factory()->create();
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

    public function ignorePackageDiscoveriesFrom()
    {
        return [];
    }
}
