<?php

namespace App\Http\Requests\Admin;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class SeoRequest extends FormRequest
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
        $seo = $this->route('seo');

        return [
            'path' => ['required', 'string', 'max:200', Rule::unique('seo_meta', 'path')->ignore($seo?->id)],
            'label' => ['required', 'string', 'max:100'],
            'title' => ['nullable', 'string', 'max:200'],
            'description' => ['nullable', 'string', 'max:300'],
            'og_image_path' => ['nullable', 'string', 'max:255'],
            'og_image_path_file' => ['nullable', 'image:allow_svg', 'mimes:jpg,jpeg,png,webp,gif,svg', 'max:5120'],
            'og_image_path_remove' => ['sometimes', 'boolean'],
            'canonical_url' => ['nullable', 'url', 'max:255'],
            'is_indexable' => ['sometimes', 'boolean'],
        ];
    }
}
