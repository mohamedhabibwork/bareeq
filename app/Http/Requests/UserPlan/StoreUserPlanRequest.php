<?php

namespace App\Http\Requests\UserPlan;

use App\Http\Requests\RequestMethods;
use Illuminate\Foundation\Http\FormRequest;

class StoreUserPlanRequest extends FormRequest
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
            'user_id' => ['required', 'integer', 'exists:users,id'],
            'plan_id' => ['required', 'integer', 'exists:plans,id'],
        ];
    }

    protected function prepareForValidation()
    {
        $this->removeNullFromRequest();
    }
}
