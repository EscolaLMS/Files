<?php

namespace EscolaLms\Files\Http\Requests;

use EscolaLms\Files\Http\Controllers\FileApiController;
use Illuminate\Foundation\Auth\User;
use Illuminate\Foundation\Http\FormRequest;

class FileListingRequest extends FormRequest
{
    /**
     * @return bool
     */
    public function authorize(): bool
    {
        /** @var User $user */
        $user = $this->user();
        return $user!=null && $user->can('list:files', FileApiController::class);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules(): array
    {
        return [
            'directory' => 'required',
            'from' => 'nullable|string|min:1',
            'count' => 'nullable|integer|min:0',
        ];
    }

    public function getAcceptableContentTypes(): array
    {
        return ['application/json'];
    }
}
