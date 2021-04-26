<?php

namespace EscolaLms\Files\Tests;

use EscolaLms\Files\EscolaLmsFilesServiceProvider;
use Illuminate\Support\Facades\Storage;

class TestCase extends \EscolaLms\Core\Tests\TestCase
{
    protected function setUp(): void
    {
        parent::setUp();
    }

    protected function getPackageProviders($app)
    {
        return [EscolaLmsFilesServiceProvider::class];
    }
}
