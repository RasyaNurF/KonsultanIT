<?php

namespace App\Http\Requests\Admin;

use App\Enums\PublishStatus;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class CareerRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    /**
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'title' => ['required', 'string', 'max:180'],
            'department' => ['nullable', 'string', 'max:100'],
            'location' => ['nullable', 'string', 'max:120'],
            'employment_type' => ['nullable', 'string', 'max:50'],
            'description' => ['nullable', 'string', 'max:10000'],
            'requirements' => ['nullable', 'string', 'max:10000'],
            'responsibilities' => ['nullable', 'string', 'max:10000'],
            'salary_range' => ['nullable', 'string', 'max:120'],
            'deadline' => $this->isMethod('post')
                ? ['nullable', 'date', 'after_or_equal:today']
                : ['nullable', 'date'],
            'is_remote' => ['sometimes', 'boolean'],
            'sort_order' => ['nullable', 'integer', 'min:0'],
            'status' => ['required', Rule::enum(PublishStatus::class)],
        ];
    }
}
