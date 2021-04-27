<?php

namespace EscolaLms\Files;

use EscolaLms\Files\Http\Exceptions\Handler;
use EscolaLms\Files\Http\Services\Contracts\FileServiceContract;
use EscolaLms\Files\Http\Services\FileService;
use Illuminate\Contracts\Debug\ExceptionHandler;
use Illuminate\Support\ServiceProvider;

class EscolaLmsFilesServiceProvider extends ServiceProvider
{
    public array $singletons = [
        FileServiceContract::class => FileService::class,
    ];

    public function boot()
    {
        $this->mergeConfigFrom(
            __DIR__.'/../config/filesystems.php',
            'filesystems'
        );
        $this->app->bind(
            ExceptionHandler::class,Handler::class,
        );
        $this->loadRoutesFrom(__DIR__ . '/routes.php');
    }
}
