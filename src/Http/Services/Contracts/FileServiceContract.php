<?php

namespace EscolaLms\Files\Http\Services\Contracts;

use Exception;
use Illuminate\Support\Collection;

interface FileServiceContract
{
    public function findAll(string $directory, array $list): array;

    public function putAll(string $directory, array $list): void;

    public function listInfo(string $directory): Collection;
}
