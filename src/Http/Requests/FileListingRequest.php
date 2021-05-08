<?php

namespace EscolaLms\Files\Http\Requests;

use Illuminate\Foundation\Auth\User;
use Illuminate\Foundation\Http\FormRequest;

class FileListingRequest extends FormRequest
{
    /**
     * @return bool
     */
    public function authorize()
    {
        /** @var User $user */
        $user = $this->user();
        return $user!=null && $user->can('list:files', 'api');
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'directory' => 'required',
            'page' => 'nullable|integer|min:1',
            'perPage' => 'nullable|integer|min:0',
        ];
    }

    public function getDirectory(): string
    {
        return $this->get('directory');
    }

    public function getPage(): int
    {
        return $this->get('page', 1);
    }

    public function getPerPage(): int
    {
        return $this->get('perPage', 50);
    }

    public function getAcceptableContentTypes()
    {
        return ['application/json'];
    }
}
