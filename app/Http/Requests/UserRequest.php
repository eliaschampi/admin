<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UserRequest extends FormRequest
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
            "code" => "required",
            "name" => "required|min:2|max:50",
            "lastname" => "required|min:5|max:80",
            "dni" => "required|min:8|max:8",
            "gender" => "required|min:1|max:1",
            "email" => "email|required|min:5|max:50",
            "phone" => "nullable|min:9|max:30",
            "address" => "nullable|min:3|max:200",
            "image" => "nullable|string",
            "state" => "nullable",
            "rol_code" => "nullable",
            "branch_code" => "required",
        ];
    }
}
