<?php

return [
    'disks' => [
        'public' => [
            'driver' => 'local',
            'root' => storage_path('app/public'),
            'url' => env('APP_URL').'/api/file/download',
            'visibility' => 'public',
        ]
    ]
];
