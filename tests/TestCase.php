<?php

namespace EscolaLms\Files\Tests;

use EscolaLms\Core\Models\User;
use EscolaLms\Files\EscolaLmsFilesServiceProvider;
use Illuminate\Contracts\Filesystem\Filesystem;
use EscolaLms\Files\Seeders\DatabaseSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Testing\TestResponse;
use Laravel\Passport\PassportServiceProvider;
use Spatie\Permission\PermissionServiceProvider;

class TestCase extends \EscolaLms\Core\Tests\TestCase
{
    protected Filesystem $disk;

    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->disk = Storage::fake('files');

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

    protected function getWithQuery(string $url, array $parameters, array $headers = []): TestResponse
    {
        if (empty($parameters)) {
            $query = $url;
        } else {
            $query = $url.'?'.http_build_query($parameters);
        }
        return $this->get($query, $headers);
    }
}
