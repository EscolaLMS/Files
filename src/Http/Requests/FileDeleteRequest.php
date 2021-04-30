<?php

namespace EscolaLms\Files\Http\Requests;

use Illuminate\Foundation\Auth\User;
use Illuminate\Foundation\Http\FormRequest;

class FileDeleteRequest extends FormRequest
{
    /**
     * @return bool
     */
    public function authorize(): bool
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
    public function rules(): array
    {
        return [
        ];
    }
}
