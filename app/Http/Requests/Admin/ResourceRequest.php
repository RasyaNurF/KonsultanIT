<?php

namespace App\Http\Requests\Admin;

use App\Enums\PublishStatus;
use App\Enums\ResourceType;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ResourceRequest extends FormRequest
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
        $resource = $this->route('resource');

        return [
            'type' => ['required', Rule::enum(ResourceType::class)],
            'title' => ['required', 'string', 'max:200'],
            'slug' => ['nullable', 'string', 'max:220', Rule::unique('resources', 'slug')->ignore($resource?->id)],
            'excerpt' => ['nullable', 'string', 'max:1000'],
            'body' => ['nullable', 'string', 'max:40000'],
            'cover_image_path' => ['nullable', 'string', 'max:255'],
            'cover_image_path_file' => ['nullable', 'image:allow_svg', 'mimes:jpg,jpeg,png,webp,gif,svg', 'max:5120'],
            'cover_image_path_remove' => ['sometimes', 'boolean'],
            'file_path' => ['nullable', 'string', 'max:255'],
            'file_path_file' => ['nullable', 'file', 'mimes:pdf,doc,docx,xls,xlsx,ppt,pptx,zip', 'max:10240'],
            'file_path_remove' => ['sometimes', 'boolean'],
            'external_url' => ['nullable', 'string', 'max:255', 'regex:/^(https?:\/\/|\/)[^\s]*$/i'],
            'register_url' => ['nullable', 'string', 'max:255', 'regex:/^(https?:\/\/|\/)[^\s]*$/i'],
            'recording_url' => ['nullable', 'string', 'max:255', 'regex:/^(https?:\/\/|\/)[^\s]*$/i'],
            'location' => ['nullable', 'string', 'max:180'],
            'organizer' => ['nullable', 'string', 'max:150'],
            'industry' => ['nullable', 'string', 'max:120'],
            'page_count' => ['nullable', 'integer', 'min:1'],
            'agenda_text' => ['nullable', 'string', 'max:5000'],
            'speakers_text' => ['nullable', 'string', 'max:5000'],
            'gallery_text' => ['nullable', 'string', 'max:5000'],
            'metrics_text' => ['nullable', 'string', 'max:5000'],
            'toc_text' => ['nullable', 'string', 'max:5000'],
            'chapters_text' => ['nullable', 'string', 'max:5000'],
            'cta_label' => ['nullable', 'string', 'max:100'],
            'cta_url' => ['nullable', 'string', 'max:255', 'regex:/^(https?:\/\/|\/)[^\s]*$/i'],
            'starts_at' => ['nullable', 'date'],
            'ends_at' => ['nullable', 'date', 'after_or_equal:starts_at'],
            'is_featured' => ['sometimes', 'boolean'],
            'sort_order' => ['nullable', 'integer', 'min:0'],
            'status' => ['required', Rule::enum(PublishStatus::class)],
            'published_at' => ['nullable', 'date'],
        ];
    }
}
