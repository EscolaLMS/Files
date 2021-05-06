<?php

namespace EscolaLms\Files\Http\Services\Contracts;

use Illuminate\Support\Collection;

interface FileServiceContract
{
    public function findAll(string $directory, array $list): array;

    public function putAll(string $directory, array $list): void;

    public function listInfo(string $directory): Collection;

    public function move(string $sourceUrl, string $destinationUrl): void;

    public function delete(string $url): void;
}
