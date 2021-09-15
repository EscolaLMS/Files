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
     * @return array $list
     */
    public function putAll(string $directory, array $list): array
    {
        $paths = [];
        /** @var UploadedFile $file */
        foreach ($list as $file) {
            $path = $this->disk->putFileAs($directory, $file, $file->getClientOriginalName());
            if ($path === false) {
                throw new PutAllException($file->getClientOriginalName(), $directory);
            }
            $paths[] = $path;
        }

        $results = collect($paths)->map(function (string $path) {
            return [
                'name' => $path,
                'created_at' => date(DATE_RFC3339),
                'mime' => $this->disk->mimeType($path),
                'url' => $this->disk->url($path),
            ];
        });

        return $results->toArray();
    }

    /**
     * @param string $directory
     * @return Collection
     */
    public function listInfo(string $directory): Collection
    {
        try {
            return collect($this->disk->listContents($directory, false))
                ->map(function (array $metadata) {
                    return [
                        'name' => $metadata['basename'],
                        'created_at' => date(DATE_RFC3339, $metadata['timestamp']),
                        'mime' => $this->disk->mimeType($metadata['path']),
                        'url' => $this->disk->url($metadata['path']),
                        'isDir' => $this->disk->mimeType($metadata['path']) === 'directory'
                    ];
                })->sortByDesc('isDir')->values();
        } catch (\LogicException $exception) {
            throw new DirectoryOutsideOfRootException($directory);
        }
    }

    /**
     * @param string $directory
     * @param string $name
     * @return Collection
     */
    public function findByName(string $directory, string $name): Collection
    {
        try {
            return collect($this->disk->listContents($directory, true))
                ->filter(function (array $metadata) use ($name) {
                    return str_contains($metadata['basename'], $name);
                })
                ->map(function (array $metadata) {
                    return [
                        'name' => $metadata['basename'],
                        'url' =>  $this->disk->url($metadata['path']),
                        'created_at' => date(DATE_RFC3339, $metadata['timestamp']),
                        'mime' => $this->disk->mimeType($metadata['path']),
                        'isDir' => $this->disk->mimeType($metadata['path']) === 'directory'
                    ];
                })->sortByDesc('isDir')->values();
        } catch (\LogicException $exception) {
            throw new DirectoryOutsideOfRootException($directory);
        }
    }

    /**
     * @param string $url
     * @throws CannotDeleteFile
     * @throws DirectoryOutsideOfRootException
     */
    public function delete(string $url): bool
    {
        $prefix = $this->disk->url('');
        if (substr($url, 0, strlen($prefix)) === $prefix) {
            $path = substr($url, strlen($prefix));
        } else {
            $path = $url;
        }
        try {
            if ($this->disk->exists($path)) {
                if ($this->disk->mimeType($path) === 'directory') {
                    $deleted = $this->disk->deleteDirectory($path);
                } else {
                    $deleted = $this->disk->delete($path);
                }
            } else {
                $deleted = false;
            }
            if (!$deleted) {
                throw new CannotDeleteFile($url);
            }
        } catch (\LogicException $e) {
            throw new DirectoryOutsideOfRootException($url);
        }

        return $deleted;
    }

    /**
     * @param string $sourceUrl
     * @param string $destinationUrl
     * @throws MoveException
     * @throws \League\Flysystem\FileExistsException
     * @throws \League\Flysystem\FileNotFoundException
     */
    public function move(string $sourceUrl, string $destinationUrl): bool
    {
        try {
            $ret = $this->disk->rename($this->urlToPath($sourceUrl), $this->urlToPath($destinationUrl));
            if (!$ret) {
                throw new MoveException($sourceUrl, $destinationUrl);
            }
        } catch (\League\Flysystem\FileNotFoundException $exception) {
            throw new MoveException($sourceUrl, $destinationUrl);
        }
        return $ret;
    }

    private function urlToPath(string $url): string
    {
        $prefix = $this->disk->url('');
        if (substr($url, 0, strlen($prefix)) === $prefix) {
            $path = substr($url, strlen($prefix));
        } else {
            $path = $url;
        }
        return $path;
    }
}
