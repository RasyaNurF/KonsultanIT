@php($fieldId = $for ?? $name)

<div {{ $attributes }}>
    <label for="{{ $fieldId }}" class="block text-[13px] font-semibold text-neutral-800">
        {{ $label }}
        @if ($required)<span class="text-red-500" aria-hidden="true">*</span>@endif
    </label>
    @if ($hint)
        <p class="mt-0.5 text-xs text-neutral-500">{{ $hint }}</p>
    @endif
    <div class="mt-2">
        {{ $slot }}
    </div>
    @error($name)
        <p class="mt-1.5 flex items-start gap-1 text-xs font-medium text-red-600">
            <x-admin.icon name="alert" class="mt-px h-3.5 w-3.5 shrink-0" /> {{ $message }}
        </p>
    @enderror
</div>
