<?php

namespace EscolaLms\Files\Http\Services;

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

    function findList(string $directory, array $list)
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

    function putList(string $directory, array $list)
    {
        /** @var UploadedFile $file */
        foreach ($list as $file) {
            if( Storage::putFileAs($directory,$file, $file->getClientOriginalName()) == false )
            {
                throw new \Exception(sprintf('Cannot put file %s to %s', $file->getClientOriginalName(), $directory ));
            }
        }
    }
}
