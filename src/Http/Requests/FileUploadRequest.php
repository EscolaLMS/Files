<?php

namespace EscolaLms\Files\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class FileUploadRequest extends FormRequest
{
    /**
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'target' => 'required',
            'file' => 'required',
        ];
    }

    public function getAcceptableContentTypes()
    {
        return ['multipart/form-data'];
    }
}
