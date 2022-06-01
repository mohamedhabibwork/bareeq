<?php

namespace App\Http\Requests\WorkerUser;

use App\Http\Requests\RequestMethods;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateWorkerUserRequest extends FormRequest
{
    use RequestMethods;

    protected array $filesKeys = ['after_images','before_images'];
    protected array $forgetIfNull = ['after_images','before_images'];
    protected array $encryption = [];

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
//            'user_id' => ['required', 'integer', 'exists:users,id'],
            'worker_id' => ['required', 'integer', 'exists:workers,id'],
//            'plan_id' => ['sometimes', 'required', 'integer', Rule::exists('user_plans', 'plan_id')->where('user_id', $this->get('user_id'))],
//            'after_images' => ['sometimes', 'required', 'array'],
//            'after_images.*' => ['sometimes', 'required', 'image'],
//            'before_images' => ['sometimes', 'required', 'array'],
//            'before_images.*' => ['sometimes', 'required', 'image'],
        ];
    }

    protected function prepareForValidation()
    {
        $this->removeNullFromRequest();
    }
}
