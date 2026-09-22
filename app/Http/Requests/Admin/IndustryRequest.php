<?php

namespace App\Http\Requests\Admin;

use App\Enums\PublishStatus;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class IndustryRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        $industry = $this->route('industry');

        return [
            'name' => ['required', 'string', 'max:150'],
            'slug' => ['nullable', 'string', 'max:180', Rule::unique('industries', 'slug')->ignore($industry?->id)],
            'description' => ['nullable', 'string', 'max:5000'],
            'image_path' => ['nullable', 'string', 'max:255'],
            'image_path_file' => ['nullable', 'image:allow_svg', 'mimes:jpg,jpeg,png,webp,gif,svg', 'max:5120'],
            'image_path_remove' => ['sometimes', 'boolean'],
            'technologies' => ['nullable', 'string', 'max:500'],
            'case_study' => ['nullable', 'string', 'max:10000'],
            'sort_order' => ['nullable', 'integer', 'min:0'],
            'status' => ['required', Rule::enum(PublishStatus::class)],
            'services' => ['nullable', 'array'],
            'services.*' => ['integer', 'exists:services,id'],
        ];
    }
}
