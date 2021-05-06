<?php

namespace EscolaLms\Files\Http\Requests;

use Illuminate\Foundation\Auth\User;
use Illuminate\Foundation\Http\FormRequest;

class FileDeleteRequest extends FormRequest
{
    /**
     * @return bool
     */
    public function authorize()
    {
        /** @var User $user */
        $user = $this->user();
        return $user!=null && $user->can('delete:files');
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'url' => ['required','string'],
        ];
    }

    public function getAcceptableContentTypes()
    {
        return ['multipart/form-data'];
    }

    public function getParamUrl(): string
    {
        return $this->get('url');
    }
}
