<?php

namespace App\Http\Requests\Admin;

use App\Enums\ClientStatus;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ClientRequest extends FormRequest
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
        $client = $this->route('client');

        return [
            'name' => ['required', 'string', 'max:150', Rule::unique('clients', 'name')->ignore($client?->id)],
            'industry' => ['nullable', 'string', 'max:100'],
            'website' => ['nullable', 'url', 'max:255'],
            'logo_path' => ['nullable', 'string', 'max:255'],
            'logo_path_file' => ['nullable', 'image:allow_svg', 'mimes:jpg,jpeg,png,webp,gif,svg', 'max:5120'],
            'logo_path_remove' => ['sometimes', 'boolean'],
            'contact_name' => ['nullable', 'string', 'max:100'],
            'contact_email' => ['nullable', 'email', 'max:255'],
            'contact_phone' => ['nullable', 'string', 'max:30'],
            'status' => ['required', Rule::enum(ClientStatus::class)],
            'notes' => ['nullable', 'string', 'max:5000'],
        ];
    }
}
