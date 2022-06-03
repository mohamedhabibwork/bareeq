<?php

namespace App\Http\Requests\Admin;

use App\Http\Requests\RequestMethods;
use Illuminate\Foundation\Http\FormRequest;

class UpdateAdminRequest extends FormRequest
{
    use RequestMethods;

    protected array $filesKeys = [];
    protected array $forgetIfNull = ['password','password_confirmation'];
    protected array $encryption = [];

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
            'email' => ['required', 'email:filter', 'unique:admins,email,' . $this->route('id')],
            'name' => ['required', 'string', 'min:3'],
            'password' => ['sometimes', 'required', 'min:6', 'confirmed']
        ];
    }

    protected function prepareForValidation()
    {
        $this->removeNullFromRequest();
    }
}
