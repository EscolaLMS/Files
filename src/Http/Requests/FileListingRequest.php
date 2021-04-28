<?php

namespace EscolaLms\Files\Http\Requests;

use EscolaLms\Files\Http\Exceptions\Handler;
use Illuminate\Foundation\Http\FormRequest;

class FileListingRequest extends FormRequest
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
//        debug_print_backtrace();
        return [
//            'directory' => 'required',
//            'from' => 'nullable|string|min:1',
//            'count' => 'nullable|integer|min:0',
        ];
    }

    public function getAcceptableContentTypes()
    {
        return ['application/json'];
    }

    protected function getValidatorInstance() {
        return parent::getValidatorInstance();
    }
}
