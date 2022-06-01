<?php

namespace App\Http\Requests\Plan;

use App\Http\Requests\RequestMethods;
use Illuminate\Foundation\Http\FormRequest;

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
            'price' => ['required', 'numeric'],
            'description' => ['required', 'string'],
            'wishing_count' => ['required', 'integer', 'min:1'],
            'status' => ['required', 'boolean'],
            'images' => ['sometimes', 'required', 'array'],
            'images.*' => ['required', 'image']
        ];
    }

    protected function prepareForValidation()
    {
        $this->removeNullFromRequest();
    }
}
