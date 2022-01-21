<?php

namespace EscolaLms\Files\Http\Requests;

use EscolaLms\Files\Enums\FilePermissionsEnum;
use Illuminate\Foundation\Http\FormRequest;

class FileFindByNameRequest extends FormRequest
{
    /**
     * @return bool
     */
    public function authorize()
    {
        /** @var User $user */
        $user = $this->user();
        return $user!=null && $user->can(FilePermissionsEnum::FILE_LIST, 'api');
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
            'name' => 'required',
            'page' => 'nullable|integer|min:1',
            'perPage' => 'nullable|integer|min:0',
        ];
    }

    public function getDirectory(): string
    {
        return $this->get('directory');
    }

    public function getName(): string
    {
        return $this->get('name');
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
