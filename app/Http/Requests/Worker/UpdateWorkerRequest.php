<?php

namespace App\Http\Requests\Worker;

use App\Http\Requests\RequestMethods;
use Illuminate\Foundation\Http\FormRequest;

class UpdateWorkerRequest extends FormRequest
{
    use RequestMethods;

    protected array $filesKeys = ['image'];
    protected array $forgetIfNull = ['password', 'name', 'phone'];
    protected array $encryption = [];

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
            'phone' => ['sometimes', 'required', 'unique:workers,phone,' . $this->route('id')],
            'name' => ['sometimes', 'required', 'string', 'min:3'],
            'password' => ['sometimes', 'required', 'min:6', 'confirmed'],
        ];
    }

    protected function prepareForValidation()
    {
        $this->removeNullFromRequest();
    }
}
