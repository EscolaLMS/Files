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

    public function register()
    {
        app()->config['filesystems.disks.files'] = [
            'driver'=>'local',
            'root'=>storage_path('/files/public'),
            'url'=>env('APP_URL').'/files',
            'visibility'=>'public',
        ];
        app()->config['filesystems.links'] = [
            public_path('files') => storage_path('files/public'),
        ];
        parent::register();
    }

    public function boot()
    {
        $this->app->bind(
            ExceptionHandler::class,Handler::class,
        );
        $this->publishes([
            __DIR__ . '/../database/seeders' => database_path('seeders'),
        ], 'files-seeders');
        $this->loadRoutesFrom(__DIR__ . '/routes.php');
    }
}
