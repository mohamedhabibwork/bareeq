<?php

namespace App\Http\Requests\Worker;

use App\Http\Requests\RequestMethods;
use Illuminate\Foundation\Http\FormRequest;

class StoreWorkerRequest extends FormRequest
{
    use RequestMethods;

    protected array $filesKeys = ['image'];
    protected array $forgetIfNull = ['password'];
    protected array $encryption = [];

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
            'phone' => ['required', 'unique:workers,phone'],
            'name' => ['required', 'string', 'min:3'],
            'password' => ['required', 'min:6', 'confirmed']
        ];
    }

    protected function prepareForValidation()
    {
        $this->removeNullFromRequest();
    }
}
