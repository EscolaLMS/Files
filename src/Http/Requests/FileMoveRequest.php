<?php

namespace EscolaLms\Files\Http\Requests;

use Illuminate\Foundation\Auth\User;
use Illuminate\Foundation\Http\FormRequest;

class FileMoveRequest extends FormRequest
{
    /**
     * @return bool
     */
    public function authorize()
    {
        /** @var User $user */
        $user = $this->user();
        return $user!=null && $user->can('move:files', 'api');
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'source_url' => ['string','required'],
            'destination_url' => ['string','required'],
        ];
    }

    public function getAcceptableContentTypes()
    {
        return ['application/json'];
    }

    public function getParamSource(): string
    {
        return $this->get('source_url');
    }

    public function getParamDestination(): string
    {
        return $this->get('destination_url');
    }
}
