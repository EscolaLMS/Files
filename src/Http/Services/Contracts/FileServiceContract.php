<?php

namespace EscolaLms\Files\Http\Services\Contracts;

interface FileServiceContract
{
    function put(string $key, $content);
}
