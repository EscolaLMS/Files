<?php


namespace EscolaLms\Files\Http\Exceptions;


use Throwable;

class PutAllException extends \Exception
{
    /**
     * PutAllException constructor.
     * @param string $filename
     * @param string $directory
     */
    public function __construct(string $filename, string $directory)
    {
        parent::__construct(sprintf('Cannot put file %s to %s', $filename, $directory));
    }
}
