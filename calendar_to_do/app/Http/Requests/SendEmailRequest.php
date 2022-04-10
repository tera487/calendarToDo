<?php

namespace App\Http\Requests;

use App\Http\Requests\ApiRequest;

class SendEmailRequest extends ApiRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'email' => 'required|email:filter|exists:users,email'
        ];
    }

    public function attributes()
    {
        return [
            'email' => 'メールアドレス',
        ];
    }
}
