<?php

namespace EscolaLms\Files\Http\Services\Contracts;

use Exception;

interface FileServiceContract
{
    /**
     * @param string $directory directory under which to check files for
     * @param array $list List of Files
     * @return boolean
     */
    function findAll(string $directory, array $list);

    /**
     * @param string $directory directory under which to file put in
     * @param array $list List of Files
     * @throws Exception
     */
    function putAll(string $directory, array $list);
}
