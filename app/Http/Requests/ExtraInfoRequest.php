<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ExtraInfoRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     * @return array
     */
    public function rules()
    {
        return [
            "student_dni" => "required",
            "religion" => "required|min:5|max:50",
            "livemode" => "required",
            "weight" => "required|numeric|between:20,100",
            "size" => "required|numeric|between:80,200",
            "live_together" => "required",
            "additional" => "nullable|min:5|max:150",
            "reg_reason" => "nullable|min:5|max:200",
        ];
    }

    public function attributes()
    {
        return [
            'livemode' => 'vínculo',
            "weight" => "peso",
            "size" => "talla",
            "additional" => "adicional",
            "reg_reason" => "razón de matrícula",
        ];
    }
}
