<?php

namespace App\Http\Requests\Admin;

use App\Http\Requests\RequestMethods;
use Illuminate\Foundation\Http\FormRequest;

class StoreAdminRequest extends FormRequest
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
            'email' => ['required', 'email:filter', 'unique:admins'],
            'name' => ['required', 'string', 'min:3'],
            'password' => ['required', 'min:6', 'confirmed']
        ];
    }

    protected function prepareForValidation()
    {
        $this->removeNullFromRequest();
    }
}
