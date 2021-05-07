<?php

namespace EscolaLms\Files\Http\Services;

use EscolaLms\Files\Http\Exceptions\CannotDeleteFile;
use EscolaLms\Files\Http\Exceptions\DirectoryOutsideOfRootException;
use EscolaLms\Files\Http\Exceptions\MoveException;
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
        $this->disk = $manager->disk();
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

    /**
     * @param string $directory
     * @param array $list
     * @throws PutAllException
     */
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

    /**
     * @param string $directory
     * @return Collection
     */
    public function listInfo(string $directory): Collection
    {
        try {
            return collect($this->disk->listContents($directory, true))
                ->map(function (array $metadata) {
                    return [
                        'name' => $metadata['basename'],
                        'created_at' => date(DATE_RFC3339, $metadata['timestamp']),
                        'mime' => $this->disk->mimeType($metadata['path']),
                        'url' => $this->disk->url($metadata['path']),

                    ];
                });
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
            if ($this->disk->mimeType($path) === 'directory') {
                $deleted = $this->disk->deleteDirectory($path);
            } else {
                $deleted = $this->disk->delete($path);
            }
            if (!$deleted) {
                throw new CannotDeleteFile($url);
            }
        } catch( \LogicException $e ) {
            throw new DirectoryOutsideOfRootException($url);
        }
    }

    /**
     * @param string $sourceUrl
     * @param string $destinationUrl
     * @throws MoveException
     * @throws \League\Flysystem\FileExistsException
     * @throws \League\Flysystem\FileNotFoundException
     */
    public function move(string $sourceUrl, string $destinationUrl): void
    {
        try {
            $ret = $this->disk->rename($this->urlToPath($sourceUrl), $this->urlToPath($destinationUrl));
            if (!$ret) {
                throw new MoveException($sourceUrl, $destinationUrl);
            }
        } catch (\League\Flysystem\FileNotFoundException $exception) {
            throw new MoveException($sourceUrl, $destinationUrl);
        }
    }

    private function urlToPath(string $url): string
    {
        $prefix = $this->disk->url('');
        if (substr($url,0,strlen($prefix)) === $prefix) {
            $path = substr($url,strlen($prefix));
        } else {
            $path = $url;
        }
        return $path;
    }
}
