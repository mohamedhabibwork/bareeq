<?php

namespace App\Http\Requests\City;

use App\Http\Requests\RequestMethods;
use Illuminate\Foundation\Http\FormRequest;

class StoreCityRequest extends FormRequest
{
    use RequestMethods;

    protected array $filesKeys = [];
    protected array $forgetIfNull = ['locations'];
    protected array $encryption = [];

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
            'name' => ['required', 'string', 'min:3','unique:cities'],
            'locations' => ['required', 'string',]
        ];
    }

    protected function prepareForValidation()
    {
        $this->removeNullFromRequest();
    }

}
