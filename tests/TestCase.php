<?php

namespace EscolaLms\Files\Tests;

use EscolaLms\Core\EscolaLmsServiceProvider;
use EscolaLms\Core\Models\User;
use EscolaLms\Files\EscolaLmsFilesServiceProvider;
use Illuminate\Contracts\Filesystem\Filesystem;
use EscolaLms\Files\Seeders\DatabaseSeeder;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Testing\TestResponse;
use Laravel\Passport\PassportServiceProvider;
use Spatie\Permission\PermissionServiceProvider;
use EscolaLms\Files\Seeders\PermissionTableSeeder;

class TestCase extends \EscolaLms\Core\Tests\TestCase
{
    protected Filesystem $disk;

    use DatabaseTransactions;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(PermissionTableSeeder::class);
        $this->disk = Storage::fake();

        $user = User::factory()->create();
        $user->givePermissionTo(
            "list:files",
            "upload:files",
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
            EscolaLmsServiceProvider::class,
            PassportServiceProvider::class,
            PermissionServiceProvider::class,
        ];
    }

    protected function getEnvironmentSetUp($app)
    {
        parent::getEnvironmentSetUp($app);
        $app['config']->set('passport.client_uuids', false);
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

    protected function deleteWithQuery(string $url, array $parameters, array $headers = []): TestResponse
    {
        if (empty($parameters)) {
            $query = $url;
        } else {
            $query = $url.'?'.http_build_query($parameters);
        }
        return $this->delete($query, $headers);
    }
}
