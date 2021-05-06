<?php

namespace EscolaLms\Files\Tests;

use EscolaLms\Files\EscolaLmsFilesServiceProvider;
use Illuminate\Contracts\Filesystem\Filesystem;
use Illuminate\Support\Facades\Storage;
use Illuminate\Testing\TestResponse;

class TestCase extends \EscolaLms\Core\Tests\TestCase
{
    protected Filesystem $disk;

    protected function setUp(): void
    {
        parent::setUp();
        $this->disk = Storage::fake();
    }

    protected function getPackageProviders($app)
    {
        return [EscolaLmsFilesServiceProvider::class];
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
