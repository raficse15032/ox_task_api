<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateProductRequest extends FormRequest
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
        if(request()->input('not_edit_image') == true){
            return [
               'title'       => 'required',
               'price'       => 'required',
               'description' => 'required',
            ];
        }
        else{
            return [
               'title'       => 'required',
               'price'       => 'required',
               'description' => 'required',
               'image'       => 'required|base64_jpg|base64_size',
            ];
        }
        
    }

    public function messages()
    {
        return [
           'base64_jpg'  => 'The image should be in jpg or png or jpeg or gif format',
           'base64_size' => 'The image should less than 1024 KB',
           'required'    => 'This :attribute field is required'
        ];
    }
}
