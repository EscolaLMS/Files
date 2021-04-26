<?php

namespace EscolaLms\Files\Http\Controllers;

use EscolaLms\Files\Http\Controllers\Swagger\FileApiSwagger;
use EscolaLms\Files\Http\Requests\FileDeleteRequest;
use EscolaLms\Files\Http\Requests\FileListingRequest;
use EscolaLms\Files\Http\Requests\FileMoveRequest;
use EscolaLms\Files\Http\Requests\FileUploadRequest;
use EscolaLms\Core\Http\Controllers\EscolaLmsBaseController;
use EscolaLms\Files\Http\Services\Contracts\FileServiceContract;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\UploadedFile;

class FileApiController extends EscolaLmsBaseController implements FileApiSwagger
{
    private FileServiceContract $service;

    /**
     * @param FileServiceContract $files
     */
    public function __construct(FileServiceContract $files)
    {
        $this->service = $files;
    }

    /**
     * @param FileUploadRequest $request
     * @return JsonResponse
     */
    public function upload(FileUploadRequest $request): JsonResponse
    {
//        $request->get('target');
        /** @var UploadedFile $file */
        foreach ($request->allFiles() as $file) {
            if ($this->service->exists())
            $this->service->store(
                $file->getClientOriginalName(),
                $file->getATime(),
                $file->getContent(),
                $file->getMimeType(),
            );
        }
        return new JsonResponse([
            'success' => true
        ], 200);
    }

    public function list(FileListingRequest $request): JsonResponse
    {
        return new JsonResponse([
            'success' => true
        ], 200);
    }

    public function move(FileMoveRequest $request): JsonResponse
    {
        return new JsonResponse([
            'success' => true
        ], 200);
    }

    public function delete(FileDeleteRequest $request): JsonResponse
    {
        return new JsonResponse([
            'success' => true
        ], 200);
    }
}
