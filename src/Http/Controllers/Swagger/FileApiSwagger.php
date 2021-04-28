<?php

namespace EscolaLms\Files\Http\Controllers\Swagger;

use EscolaLms\Files\Http\Requests\FileDeleteRequest;
use EscolaLms\Files\Http\Requests\FileListingRequest;
use EscolaLms\Files\Http\Requests\FileMoveRequest;
use EscolaLms\Files\Http\Requests\FileUploadRequest;
use Illuminate\Http\JsonResponse;

if (file_exists(__DIR__.'/../../oa_version.php')) {
    require __DIR__.'/../../oa_version.php';
}

/**
 * SWAGGER_VERSION
 */
interface FileApiSwagger
{
    /**
     * @OA\Get(
     *     path="/api/file/list",
     *     summary="Lists files prefixed by given directory name",
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\MediaType(
     *            mediaType="application/json",
     *            @OA\Schema(
     *                type="object",
     *                required={"directory"},
     *                @OA\Property(property="directory", type="string", default="/"),
     *                @OA\Property(property="from", type="string", default="."),
     *                @OA\Property(property="count", type="uint", default="50"),
     *            )
     *         )
     *     ),
     *     @OA\Response(
     *          response=200,
     *          description="successful operation",
     *         @OA\MediaType(
     *            mediaType="application/json",
     *            @OA\Schema(
     *                type="array",
     *                @OA\Items(
     *                    type="object",
     *                    @OA\Property(
     *                        property="name",
     *                        type="string",
     *                    ),
     *                    @OA\Property(
     *                        property="created_at",
     *                        type="string",
     *                        format="datetime",
     *                    ),
     *                    @OA\Property(
     *                        property="mime",
     *                        type="string",
     *                        format="mime",
     *                    ),
     *                    @OA\Property(
     *                        property="url",
     *                        type="string",
     *                        format="url",
     *                    ),
     *                ),
     *            )
     *         )
     *      ),
     *     @OA\Response(
     *          response=401,
     *          description="endpoint requires authentication",
     *     ),
     *     @OA\Response(
     *          response=302,
     *          description="request contains invalid parameters",
     *     ),
     *     @OA\Response(
     *          response=403,
     *          description="user doesn't have required access rights",
     *      ),
     *     @OA\Response(
     *          response=500,
     *          description="server-side error",
     *      ),
     * )
     *
     * @param FileListingRequest $request
     * @return JsonResponse
     */
    public function list(FileListingRequest $request): JsonResponse;

    /**
     * @OA\Post(
     *     path="/api/file/upload",
     *     summary="Upload files using multipart form-data",
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\MediaType(
     *            mediaType="multipart/form-data",
     *            @OA\Schema(
     *                type="object",
     *                required={"file[]","target"},
     *                @OA\Property(
     *                    property="file[]",
     *                    type="array",
     *                    @OA\Items(
     *                        type="string",
     *                        format="binary",
     *                    ),
     *                ),
     *                @OA\Property(
     *                    property="target",
     *                    type="string",
     *                    default="/",
     *                ),
     *            )
     *         )
     *     ),
     *     @OA\Response(
     *          response=200,
     *          description="successful operation",
     *      ),
     *     @OA\Response(
     *          response=401,
     *          description="endpoint requires authentication",
     *      ),
     *     @OA\Response(
     *          response=403,
     *          description="user doesn't have required access rights",
     *      ),
     *     @OA\Response(
     *          response=409,
     *          description="one of the uploaded files already exists",
     *      ),
     *     @OA\Response(
     *          response=422,
     *          description="server-side error",
     *      ),
     * )
     *
     * @param FileUploadRequest $request
     * @return JsonResponse
     */
    public function upload(FileUploadRequest $request): JsonResponse;

    /**
     * @OA\Post(
     *     path="/api/file/move",
     *     summary="Move the file from one path to another",
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\MediaType(
     *            mediaType="multipart/form-data",
     *            @OA\Schema(
     *                type="object",
     *                required={"source","destination"},
     *                @OA\Property(property="source", type="string"),
     *                @OA\Property(property="destination", type="string"),
     *            )
     *         )
     *     ),
     *     @OA\Response(
     *          response=200,
     *          description="successful operation",
     *      ),
     *     @OA\Response(
     *          response=302,
     *          description="invalid request",
     *      ),
     *     @OA\Response(
     *          response=401,
     *          description="endpoint requires authentication",
     *      ),
     *     @OA\Response(
     *          response=403,
     *          description="user doesn't have required access rights",
     *      ),
     *     @OA\Response(
     *          response=500,
     *          description="server-side error",
     *      ),
     * )
     *
     * @param FileMoveRequest $request
     * @return JsonResponse
     */
    public function move(FileMoveRequest $request): JsonResponse;

    /**
     * @OA\Delete(
     *     path="/api/file/delete",
     *     summary="Delete given file",
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\MediaType(
     *            mediaType="multipart/form-data",
     *            @OA\Schema(
     *                type="object",
     *                required={"file"},
     *                @OA\Property(property="file", type="string"),
     *            )
     *         )
     *     ),
     *     @OA\Response(
     *          response=200,
     *          description="successful operation",
     *      ),
     *     @OA\Response(
     *          response=401,
     *          description="endpoint requires authentication",
     *      ),
     *     @OA\Response(
     *          response=403,
     *          description="user doesn't have required access rights",
     *      ),
     *     @OA\Response(
     *          response=500,
     *          description="server-side error",
     *      ),
     * )
     *
     * @param FileDeleteRequest $request
     * @return JsonResponse
     */
    public function delete(FileDeleteRequest $request): JsonResponse;
}
