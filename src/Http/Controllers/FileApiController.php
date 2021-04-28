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
     * @throws \Exception
     */
    public function upload(FileUploadRequest $request): JsonResponse
    {
        $target = $request->get('target');
        $files = $request->file('file');

        $list = $this->service->findAll($target, $files);
        if (!empty($list)) {
            return new JsonResponse([
                'error' => sprintf("Following files already exist: %s", join(", ", $list))
            ], 409);
        }

        $this->service->putAll($target, $files);

        return new JsonResponse([
            'success' => true
        ], 200);
    }

    public function list(FileListingRequest $request): JsonResponse
    {
//        $list = $this->service->list();
        $list = [
            ['name'=>'test.png', 'created_at'=>date(DATE_RFC3339),'mime'=>'image/png', 'url'=>'/test.png'],
            ['name'=>'test.json', 'created_at'=>date(DATE_RFC3339),'mime'=>'application/json', 'url'=>'/test.json'],
        ];
        return new JsonResponse($list, 200);
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
