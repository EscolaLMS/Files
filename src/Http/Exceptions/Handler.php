<?php
namespace EscolaLms\Files\Http\Exceptions;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Throwable;

class Handler extends \Illuminate\Foundation\Exceptions\Handler
{
    /**
     * Render an exception into an HTTP response.
     *
     * @param Request $request
     * @param Throwable $exception
     * @return Response
     *
     * @throws Throwable
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

    public function report(Throwable $e)
    {
//        dd($e->getMessage());
        return parent::report($e);
    }
}
