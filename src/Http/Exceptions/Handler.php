<?php
namespace EscolaLms\Files\Http\Exceptions;

use Illuminate\Http\JsonResponse;
use Throwable;

class Handler extends \Illuminate\Foundation\Exceptions\Handler
{
    /**
     * Render an exception into an HTTP response.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Throwable  $e
     * @return \Symfony\Component\HttpFoundation\Response
     *
     * @throws \Throwable
     */
    public function render($request, Throwable $exception)
    {
        if ($exception instanceof PutAllException)
        {
            return new JsonResponse([
                'message' => $exception->getMessage(),
            ], 422);
        }
        else
        {
            return parent::render($request, $exception);
        }
    }
}
