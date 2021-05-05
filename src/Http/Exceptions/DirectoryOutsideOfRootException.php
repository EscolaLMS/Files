<?php
namespace EscolaLms\Files\Http\Exceptions;


class DirectoryOutsideOfRootException extends \LogicException
{
    public function __construct(string $directory)
    {
        parent::__construct(sprintf('Directory "%s" is outside of allowed root', $directory));
    }
}
