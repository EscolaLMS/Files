<?php

namespace EscolaLms\Files\Http\Services;

use EscolaLms\Files\Http\Exceptions\CannotDeleteFile;
use EscolaLms\Files\Http\Exceptions\DirectoryOutsideOfRootException;
use EscolaLms\Files\Http\Exceptions\PutAllException;
use EscolaLms\Files\Http\Services\Contracts\FileServiceContract;
use Illuminate\Filesystem\FilesystemAdapter;
use Illuminate\Filesystem\FilesystemManager;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Collection;

class FileService implements FileServiceContract
{
    private FilesystemAdapter $disk;

    public function __construct(FilesystemManager $manager)
    {
        $this->disk = $manager->disk('files');
    }

    public function findAll(string $directory, array $list): array
    {
        $ret = [];
        /** @var UploadedFile $file */
        foreach ($list as $file) {
            $name = $file->getClientOriginalName();
            $path = $directory.'/'.$name;

            if ($this->disk->exists($path)) {
                $ret []= $path;
            }
        }
        return $ret;
    }

    public function putAll(string $directory, array $list): void
    {
        /** @var UploadedFile $file */
        foreach ($list as $file) {
            if( $this->disk->putFileAs($directory,$file, $file->getClientOriginalName()) == false )
            {
                throw new PutAllException($file->getClientOriginalName(),$directory);
            }
        }
    }

    public function listInfo(string $directory): Collection
    {
        try {
            return collect($this->disk->files($directory))
                ->map(function (string $path) {
                    return [
                        'name' => basename($path),
                        'created_at' => date(DATE_RFC3339, $this->disk->getTimestamp($path)),
                        'mime' => $this->disk->mimeType($path),
                        'url' => $this->disk->url($path),

                    ];
                }
                );
        }
        catch( \LogicException $exception)
        {
            throw new DirectoryOutsideOfRootException($directory);
        }
    }

    /**
     * @param string $url
     * @throws CannotDeleteFile
     * @throws DirectoryOutsideOfRootException
     */
    public function delete(string $url): void
    {
        $prefix = $this->disk->url('');
        if (substr($url,0,strlen($prefix)) === $prefix) {
            $path = substr($url,strlen($prefix));
        } else {
            $path = $url;
        }
        try {
            $deleted = $this->disk->delete($path);
            if (!$deleted) {
                throw new CannotDeleteFile($url);
            }
        } catch( \LogicException $e ) {
            throw new DirectoryOutsideOfRootException($url);
        }
    }
}
