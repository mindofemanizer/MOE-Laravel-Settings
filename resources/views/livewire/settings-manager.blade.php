@php
    $group = $this->activeGroup;
    $inputClass = 'w-full min-w-0 border border-outline-variant/40 rounded-lg px-3.5 py-2.5 text-body text-on-surface bg-surface focus:outline-none focus:ring-2 focus:ring-primary/20 focus:border-primary transition-colors placeholder:text-outline-variant';
@endphp

<div class="w-full min-w-0">
    @if ($group)
        <form wire:submit.prevent="save" class="space-y-5">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-x-5 gap-y-5">
                @foreach ($group->fields as $field)
                    @php
                        $isWide = in_array($field->type, [
                            \Moe\Settings\Schema\SettingField::TYPE_TEXTAREA,
                            \Moe\Settings\Schema\SettingField::TYPE_LIVEWIRE_COMPONENT,
                            \Moe\Settings\Schema\SettingField::TYPE_CHECKBOX_GROUP,
                        ], true);
                        $isToggle = $field->type === \Moe\Settings\Schema\SettingField::TYPE_TOGGLE;
                    @endphp

                    @if ($isToggle)
                        {{-- Toggle: full-width row, label kiri + switch kanan --}}
                        <div class="md:col-span-2 min-w-0">
                            <div class="flex items-start justify-between gap-4 rounded-xl border border-outline-variant/30 bg-surface-container-low/60 px-4 py-3">
                                <div class="min-w-0 space-y-0.5">
                                    <p class="font-body text-body font-semibold text-on-surface">{{ $field->label }}</p>
                                    @if ($field->description)
                                        <p class="text-body-sm text-on-surface-variant leading-snug">{{ $field->description }}</p>
                                    @endif
                                </div>
                                <label class="relative inline-flex items-center cursor-pointer flex-shrink-0 mt-0.5">
                                    <input type="checkbox" wire:model="values.{{ $field->key }}" class="sr-only peer" aria-label="{{ $field->label }}">
                                    <div class="w-11 h-6 bg-surface-variant peer-focus:outline-none peer-focus:ring-2 peer-focus:ring-primary/20 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-outline-variant after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-primary"></div>
                                </label>
                            </div>
                        </div>
                    @else
                        <div class="{{ $isWide ? 'md:col-span-2' : '' }} min-w-0 space-y-1.5">
                            <label class="font-body text-body font-semibold text-on-surface block" for="setting-{{ $field->key }}">
                                {{ $field->label }}
                            </label>

                            @if ($field->type === \Moe\Settings\Schema\SettingField::TYPE_TEXTAREA)
                                <textarea
                                    id="setting-{{ $field->key }}"
                                    wire:model="values.{{ $field->key }}"
                                    rows="3"
                                    placeholder="{{ $field->placeholder }}"
                                    class="{{ $inputClass }} resize-y min-h-[5.5rem]"
                                ></textarea>

                            @elseif ($field->type === \Moe\Settings\Schema\SettingField::TYPE_SELECT)
                                <select
                                    id="setting-{{ $field->key }}"
                                    wire:model="values.{{ $field->key }}"
                                    class="{{ $inputClass }} cursor-pointer"
                                >
                                    @foreach ($field->options as $optVal => $optLabel)
                                        <option value="{{ $optVal }}">{{ $optLabel }}</option>
                                    @endforeach
                                </select>

                            @elseif ($field->type === \Moe\Settings\Schema\SettingField::TYPE_CHECKBOX_GROUP)
                                <div class="flex flex-wrap gap-2">
                                    @foreach ($field->options as $optVal => $optLabel)
                                        <label class="inline-flex items-center gap-2 border border-outline-variant/40 rounded-lg px-3 py-2 text-body cursor-pointer hover:bg-surface-container transition-colors">
                                            <input type="checkbox" wire:model="values.{{ $field->key }}" value="{{ $optVal }}" class="rounded border-outline-variant accent-primary">
                                            <span>{{ $optLabel }}</span>
                                        </label>
                                    @endforeach
                                </div>

                            @elseif ($field->type === \Moe\Settings\Schema\SettingField::TYPE_PASSWORD)
                                <input
                                    id="setting-{{ $field->key }}"
                                    type="password"
                                    wire:model="values.{{ $field->key }}"
                                    placeholder="{{ $passwordMask[$field->key] ?? false ? '•••••••• (tidak diubah)' : $field->placeholder }}"
                                    class="{{ $inputClass }}"
                                    autocomplete="new-password"
                                >

                            @elseif ($field->type === \Moe\Settings\Schema\SettingField::TYPE_IMAGE)
                                <div class="flex flex-col sm:flex-row sm:items-center gap-3">
                                    @if (! empty($values[$field->key]))
                                        <img
                                            src="{{ asset($values[$field->key]) }}"
                                            alt="{{ $field->label }}"
                                            class="h-16 w-16 rounded-xl border border-outline-variant/40 object-cover flex-shrink-0"
                                        >
                                    @else
                                        <div class="h-16 w-16 rounded-xl border border-dashed border-outline-variant/50 bg-surface-container-low flex items-center justify-center flex-shrink-0">
                                            <span class="material-symbols-outlined text-outline text-[22px]">image</span>
                                        </div>
                                    @endif
                                    <div class="min-w-0 flex-1">
                                        <input
                                            id="setting-{{ $field->key }}"
                                            type="file"
                                            wire:model="imageUploads.{{ $field->key }}"
                                            accept="image/*"
                                            class="block w-full text-body-sm text-on-surface-variant file:mr-3 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-body-sm file:font-semibold file:bg-secondary-container file:text-on-secondary-container hover:file:opacity-90 file:cursor-pointer cursor-pointer"
                                        >
                                        <div wire:loading wire:target="imageUploads.{{ $field->key }}" class="text-body-sm text-on-surface-variant mt-1">
                                            Mengunggah…
                                        </div>
                                    </div>
                                </div>

                            @elseif ($field->type === \Moe\Settings\Schema\SettingField::TYPE_LIVEWIRE_COMPONENT && $field->componentName)
                                @php
                                    $componentKey  = $field->key;
                                    $componentVal  = $this->values[$field->key] ?? null;
                                    $componentName = $field->componentName;
                                    $isAlpineOnly  = $componentName === 'admin.components.map-picker';
                                @endphp
                                @if ($isAlpineOnly)
                                    @include('livewire.' . str_replace('.', '/', $componentName), [
                                        'fieldKey'   => $componentKey,
                                        'fieldValue' => $componentVal,
                                    ])
                                @else
                                    @livewire($componentName, ['key' => $componentKey, 'value' => $componentVal], key($componentKey))
                                @endif

                            @else
                                <input
                                    id="setting-{{ $field->key }}"
                                    type="{{ $field->type === \Moe\Settings\Schema\SettingField::TYPE_NUMBER ? 'number' : 'text' }}"
                                    wire:model="values.{{ $field->key }}"
                                    placeholder="{{ $field->placeholder }}"
                                    class="{{ $inputClass }}"
                                >
                            @endif

                            @if ($field->description)
                                <p class="text-body-sm text-on-surface-variant leading-snug">{{ $field->description }}</p>
                            @endif
                        </div>
                    @endif
                @endforeach
            </div>

            <div class="pt-1 border-t border-surface-container flex flex-col-reverse sm:flex-row sm:items-center sm:justify-end gap-3">
                <button
                    type="submit"
                    class="inline-flex w-full sm:w-auto items-center justify-center gap-2 bg-primary text-on-primary px-6 py-2.5 rounded-lg font-h3 shadow-sm hover:opacity-90 active:scale-[0.98] transition-all disabled:opacity-60"
                    wire:loading.attr="disabled"
                >
                    <span class="material-symbols-outlined text-icon" wire:loading.remove wire:target="save">save</span>
                    <span class="material-symbols-outlined text-icon animate-spin" wire:loading wire:target="save">progress_activity</span>
                    <span wire:loading.remove wire:target="save">Simpan Perubahan</span>
                    <span wire:loading wire:target="save">Menyimpan…</span>
                </button>
            </div>
        </form>
    @endif
</div>
