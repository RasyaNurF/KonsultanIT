<?php

namespace App\Http\Requests\Admin;

use App\Enums\AnnouncementFrequency;
use App\Enums\AnnouncementPlacement;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class AnnouncementRequest extends FormRequest
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
            'placement' => ['required', Rule::enum(AnnouncementPlacement::class)],
            'title' => ['nullable', 'string', 'max:200'],
            'message' => ['required', 'string', 'max:2000'],
            'link_label' => ['nullable', 'string', 'max:100'],
            'link_url' => ['nullable', 'string', 'max:255', 'regex:/^(https?:\/\/|\/)[^\s]*$/i'],
            'image_path' => ['nullable', 'string', 'max:255'],
            'image_path_file' => ['nullable', 'image:allow_svg', 'mimes:jpg,jpeg,png,webp,gif,svg', 'max:5120'],
            'image_path_remove' => ['sometimes', 'boolean'],
            'frequency' => ['required', Rule::enum(AnnouncementFrequency::class)],
            'frequency_days' => ['nullable', 'required_if:frequency,days', 'integer', 'min:1', 'max:365'],
            'is_dismissible' => ['sometimes', 'boolean'],
            'is_active' => ['sometimes', 'boolean'],
            'starts_at' => ['nullable', 'date'],
            'ends_at' => ['nullable', 'date', 'after_or_equal:starts_at'],
            'sort_order' => ['nullable', 'integer', 'min:0'],
        ];
    }
}
