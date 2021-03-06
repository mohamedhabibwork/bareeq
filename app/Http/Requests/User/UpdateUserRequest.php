<?php

namespace App\Http\Requests\User;

use App\Http\Requests\RequestMethods;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateUserRequest extends FormRequest
{
    use RequestMethods;

    protected array $filesKeys = ['image'];
    protected array $forgetIfNull = ['password','password_confirmation','start_time','end_time','lat','lng'];
    protected array $encryption = [];

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
            'phone' => ['sometimes', 'required', 'unique:users,phone,' . $this->route('id'), 'min:10', 'max:13', 'starts_with:01'],
            'name' => ['sometimes', 'required', 'string', 'min:3'],
            'address' => ['sometimes', 'required', 'string', 'min:3'],
            'city_id' => ['sometimes','required', 'numeric', 'exists:cities,id'],
            'password' => ['sometimes', 'required', 'min:6', 'confirmed'],
            'start_time' => ['sometimes', 'required', 'date_format:H:i'],
            'end_time' => ['sometimes', 'required', 'date_format:H:i', 'after_or_equal:start_time'],
            'wish_day' => ['sometimes', 'required', 'string', Rule::in(['Saturday', 'Sunday', 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday'])],
            'lat' => ['sometimes', 'required', 'integer'],
            'lng' => ['sometimes', 'required', 'integer'],
            'status' => Rule::when(auth('web')->check(), ['required', 'boolean'], ['exclude']),
        ];
    }

    protected function prepareForValidation()
    {

        $this->removeNullFromRequest();
    }
}
