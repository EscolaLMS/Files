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
        return $user!=null && $user->can('list:files');
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
        $page = $this->get('page');
        return $page == null ? 1 : $page;
    }

    public function getPerPage(): int
    {
        $perPage = $this->get('perPage');
        return $perPage == null ? 50 : $perPage;
    }

    public function getAcceptableContentTypes()
    {
        return ['application/json'];
    }
}
