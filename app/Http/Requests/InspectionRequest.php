<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class InspectionRequest extends FormRequest
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
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
            "inspection_type" => "required",
            "entity_type" => "required",
            "entity_identifier" => "required|max:8|min:8",
            "state" => "required",
            "description" => "required|max:400",
            "additional" => "required|max:150",
        ];
    }
}
