<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class CourseRequest extends FormRequest
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
        $rule = $this->method() === 'POST' ? Rule::unique('course', 'name') : 'required';
        return [
            "name" => [
                "max:50",
                $rule,
            ],
            "type" => "required|max:3",
        ];
    }
}
