<?php

namespace App\Http\Requests\Admin;

use App\Enums\PublishStatus;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class SolutionRequest extends FormRequest
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
        $solution = $this->route('solution');

        return [
            'solution_category_id' => ['required', 'integer', 'exists:solution_categories,id'],
            'title' => ['required', 'string', 'max:180'],
            'slug' => ['nullable', 'string', 'max:200', Rule::unique('solutions', 'slug')->ignore($solution?->id)],
            'subtitle' => ['nullable', 'string', 'max:255'],
            'excerpt' => ['nullable', 'string', 'max:1000'],
            'body' => ['nullable', 'string', 'max:20000'],
            'logo_path' => ['nullable', 'string', 'max:255'],
            'logo_path_file' => ['nullable', 'image:allow_svg', 'mimes:jpg,jpeg,png,webp,gif,svg', 'max:5120'],
            'logo_path_remove' => ['sometimes', 'boolean'],
            'cover_image_path' => ['nullable', 'string', 'max:255'],
            'cover_image_path_file' => ['nullable', 'image:allow_svg', 'mimes:jpg,jpeg,png,webp,gif,svg', 'max:5120'],
            'cover_image_path_remove' => ['sometimes', 'boolean'],
            'partner_name' => ['nullable', 'string', 'max:150'],
            'cta_label' => ['nullable', 'string', 'max:100'],
            'cta_url' => ['nullable', 'string', 'max:255', 'regex:/^(https?:\/\/|\/)[^\s]*$/i'],
            'features_text' => ['nullable', 'string', 'max:5000'],
            'benefits_text' => ['nullable', 'string', 'max:5000'],
            'is_featured' => ['sometimes', 'boolean'],
            'sort_order' => ['nullable', 'integer', 'min:0'],
            'status' => ['required', Rule::enum(PublishStatus::class)],
        ];
    }
}
