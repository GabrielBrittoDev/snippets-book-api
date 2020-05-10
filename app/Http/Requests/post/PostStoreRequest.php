<?php

namespace App\Http\Requests\post;

use Illuminate\Foundation\Http\FormRequest;

class PostStoreRequest extends FormRequest
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
                'description' => 'min:4|max:50|required',
                'image' => 'min:3|max:230',
                'snippet' => 'min:2|max:280|required',
                'restrict' => 'required'
            ];
    }
}
