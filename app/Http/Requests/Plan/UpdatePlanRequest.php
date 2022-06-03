<?php

namespace App\Http\Requests\Plan;

use App\Http\Requests\RequestMethods;
use App\Models\Plan;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdatePlanRequest extends FormRequest
{
    use RequestMethods;

    protected array $filesKeys = ['images'];
    protected array $forgetIfNull = ['images'];
    protected array $encryption = [];

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
            'name' => ['required', 'string'],
            'price' => ['sometimes','required', 'numeric','min:0'],
            'description' => ['sometimes','required', 'string'],
            'wishing_count' => ['sometimes','required', 'integer', 'min:1'],
            'type' => ['sometimes','required', 'int',Rule::in(array_values(Plan::TYPE))],
            'status' => ['sometimes','required', 'boolean'],
            'images' => ['sometimes', 'required', 'array'],
            'images.*' => ['required', 'image']
        ];
    }

    protected function prepareForValidation()
    {
        $this->removeNullFromRequest();
    }
}
