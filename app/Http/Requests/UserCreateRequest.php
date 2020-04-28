<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UserCreateRequest extends FormRequest
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

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'name' => 'min:4|max:80|required',
            'username' => 'min:4|max:30|required|unique:users',
            'email' => 'required|email|unique:users',
            'password' => 'required|min:2|max:20'
        ];
    }


}
