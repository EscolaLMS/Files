<?php

namespace EscolaLms\Files\Tests;

use EscolaLms\Core\Models\User;
use EscolaLms\Files\EscolaLmsFilesServiceProvider;
use EscolaLms\Files\Seeders\DatabaseSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Laravel\Passport\PassportServiceProvider;
use Spatie\Permission\PermissionServiceProvider;

class TestCase extends \EscolaLms\Core\Tests\TestCase
{
    use RefreshDatabase;

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
        return [
            ...parent::getPackageProviders($app),
            EscolaLmsFilesServiceProvider::class,
            PassportServiceProvider::class,
            PermissionServiceProvider::class,
        ];
    }

    protected function getEnvironmentSetUp($app)
    {
        parent::getEnvironmentSetUp($app);
//        $app['config']->set('passport.client_uuids', false);
    }

    public function ignorePackageDiscoveriesFrom()
    {
        return [];
    }

    protected function shouldSeed()
    {
        return true;
    }

    protected function seeder()
    {
        return DatabaseSeeder::class;
    }
}
