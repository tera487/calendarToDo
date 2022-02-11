<?php

namespace App\Http\Requests;

class ToDoRequest extends ApiRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'title' => 'required',
            'content' => 'required',
            'start_date' => 'required',
            'end_date' => 'nullable'
        ];
    }

    public function attributes()
    {
        return [
            'title' => 'タイトル',
            'content' => '内容',
            'start_date' => '開始日',
            'end_date' => '期限',
        ];
    }
    
}
