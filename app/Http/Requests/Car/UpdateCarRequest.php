<?php

namespace App\Http\Requests\Car;

use App\Http\Requests\RequestMethods;
use Illuminate\Foundation\Http\FormRequest;

class UpdateCarRequest extends FormRequest
{
    use RequestMethods;

    protected array $filesKeys = ['image'];
    protected array $forgetIfNull = ['image'];
    protected array $encryption = [];

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
            'type' => ['required', 'string'],
            'color' => ['required', 'string'],
            'plate_number' => ['required', 'string'],
            'user_id' => ['required', 'integer', 'exists:users,id'],
            'image' => ['sometimes', 'required', 'image'],
        ];
    }

    protected function prepareForValidation()
    {
        $this->removeNullFromRequest();
    }
}
