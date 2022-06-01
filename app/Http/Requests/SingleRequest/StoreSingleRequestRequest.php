<?php

namespace App\Http\Requests\SingleRequest;

use App\Http\Requests\RequestMethods;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreSingleRequestRequest extends FormRequest
{
    use RequestMethods;

    protected array $filesKeys = [];
    protected array $forgetIfNull = [];
    protected array $encryption = [];

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
            'car_name' => ['required', 'string'],
            'car_type' => ['required', 'string'],
            'phone' => ['required', 'string'],
            'address' => ['required', 'string'],
            'car_area' => ['required', 'string'],
            'user_id' => Rule::when(auth('web')->check(), ['required', 'integer', 'exists:users,id'], ['exclude']),
        ];
    }

    protected function prepareForValidation()
    {
        $this->removeNullFromRequest();
    }
}
