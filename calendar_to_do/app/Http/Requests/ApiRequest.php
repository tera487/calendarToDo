<?php

namespace App\Http\Requests;

use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;

class ApiRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }


    public function messages()
    {
        return [
            'required' => ':attributeは入力が必須です',
        ];
    }


    /**
     * [override] バリデーション失敗時ハンドリング
     *
     * @param Validator $validator
     * @throw HttpResponseException
     * @see FormRequest::failedValidation()
     */
    protected function failedValidation(Validator $validator) {
        // $response['status']  = 400;
        // $response['statusText'] = 'Failed validation.';
        $data  = $validator->errors();
        // throw new HttpResponseException(
        //     response()->json( $response, 200 )
        // );
        throw new HttpResponseException(response()->json($data, 422));
    }
}
