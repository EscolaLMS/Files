<?php
namespace EscolaLms\Files\Http\Exceptions;

class CannotDeleteFile extends \Exception
{
    /**
     * CannotDeleteFile constructor.
     * @param string $filename
     */
    public function __construct(string $filename)
    {
        parent::__construct(sprintf('Failed to delete the file "%s"', $filename));
    }
}
