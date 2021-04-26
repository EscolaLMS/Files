<?php

namespace EscolaLms\Files;

use EscolaLms\Core\Providers\Injectable;
use EscolaLms\Files\Http\Services\Contracts\FileServiceContract;
use EscolaLms\Files\Http\Services\FileService;
use Illuminate\Support\ServiceProvider;

class EscolaLmsFilesServiceProvider extends ServiceProvider
{
    use Injectable;

    private const CONTRACTS = [
        FileServiceContract::class => FileService::class,
    ];

    public function register()
    {
        $this->injectContract(self::CONTRACTS);
    }

    public function boot()
    {
        $this->mergeConfigFrom(
            __DIR__.'/../config/filesystems.php',
            'filesystems'
        );
        $this->loadRoutesFrom(__DIR__ . '/routes.php');
    }
}
