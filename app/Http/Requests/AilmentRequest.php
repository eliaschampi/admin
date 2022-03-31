<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class AilmentRequest extends FormRequest
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
            "student_dni" => "required",
            "type" => "required|max:2",
            "ailment" => "required|max:100",
            "cause" => "nullable|max:100",
            "treatment" => "nullable|max:200",
        ];
    }
}
