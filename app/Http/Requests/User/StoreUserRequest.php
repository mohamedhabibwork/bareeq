<?php

namespace App\Http\Requests\User;

use App\Http\Requests\RequestMethods;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreUserRequest extends FormRequest
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
            'phone' => ['required', 'unique:users,phone'],
            'name' => ['required', 'string', 'min:3'],
            'password' => ['required', 'min:6', 'confirmed'],
            'start_time' => ['sometimes', 'required', 'date_format:h:i'],
            'end_time' => ['sometimes', 'required', 'date_format:h:i', 'after_or_equal:start_time'],
            'lat' => ['sometimes', 'required', 'integer'],
            'lng' => ['sometimes', 'required', 'integer'],
            'wish_day' => ['sometimes', 'required', 'string', Rule::in(['Saturday', 'Sunday', 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday'])],
            'status' => Rule::when(auth('web')->check(), ['required', 'boolean'], ['exclude']),
        ];
    }

    protected function prepareForValidation()
    {
        $this->removeNullFromRequest();
    }
}
