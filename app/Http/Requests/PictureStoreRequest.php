<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class PictureStoreRequest extends FormRequest
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
            'title' => 'required|string|max:255',
            'picture' => 'required|image'
        ];
    }
    public function messages()
    {
        return [
            'title.required' => 'A title is required',
            'title.max' => 'The title is too long (255)',
            'picture.required'  => 'A picture is required',
            'picture.type'  => 'The picture must be a picture',
        ];
    }
}
