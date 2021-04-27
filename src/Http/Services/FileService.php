<?php

namespace EscolaLms\Files\Http\Services;

use EscolaLms\Files\Http\Exceptions\PutAllException;
use EscolaLms\Files\Http\Services\Contracts\FileServiceContract;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

class FileService implements FileServiceContract
{
    private string $disk;

    public function __construct()
    {
        $this->disk = 'files';
    }

    public function findAll(string $directory, array $list)
    {
        $ret = [];
        /** @var UploadedFile $file */
        foreach ($list as $file) {
            $name = $file->getClientOriginalName();
            $path = $directory.'/'.$name;
            if (Storage::exists($path)) {
                $ret []= $path;
            }
        }
        return $ret;
    }

    public function putAll(string $directory, array $list)
    {
        /** @var UploadedFile $file */
        foreach ($list as $file) {
            if( Storage::putFileAs($directory,$file, $file->getClientOriginalName()) == false )
            {
                throw new PutAllException($file->getClientOriginalName(),$directory);
            }
        }
    }
}
