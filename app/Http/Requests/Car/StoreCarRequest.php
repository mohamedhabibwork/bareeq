<?php

namespace App\Http\Requests\Car;

use App\Http\Requests\RequestMethods;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreCarRequest extends FormRequest
{
    use RequestMethods;

    protected array $filesKeys = ['image'];
    protected array $forgetIfNull = ['image','user_id'];
    protected array $encryption = [];

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
            'type' => ['sometimes', 'required', 'string'],
            'color' => ['sometimes', 'required', 'string'],
            'plate_number' => ['sometimes', 'required', 'string'],
            'user_id' => Rule::when(auth('web')->check(), ['required', 'integer', 'exists:users,id'], ['sometimes', 'exclude']),
            'image' => ['sometimes', 'required', 'image'],
        ];
    }

    protected function prepareForValidation()
    {
        $this->removeNullFromRequest();
    }
}
