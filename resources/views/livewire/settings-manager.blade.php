<div class="space-y-6">
    @if (session('settings_saved'))
        <div class="rounded-lg bg-green-100 px-4 py-3 text-sm text-green-800">
            {{ session('settings_saved') }}
        </div>
    @endif

    <div class="flex flex-col gap-6 lg:flex-row">
        {{-- Sidebar tabs --}}
        <div class="lg:w-64 lg:flex-shrink-0">
            <nav class="space-y-1">
                @foreach ($this->groups as $g)
                    <button
                        wire:click="$set('activeTab', '{{ $g->key }}')"
                        class="w-full rounded-r-lg px-4 py-3 text-left text-sm transition-all {{ $activeTab === $g->key ? 'bg-primary/10 font-semibold text-primary' : 'text-gray-600 hover:bg-gray-100' }}"
                    >
                        <span class="material-symbols-outlined align-middle text-base">{{ $g->icon }}</span>
                        <span class="ml-1">{{ $g->label }}</span>
                    </button>
                @endforeach
            </nav>
        </div>

        {{-- Panel --}}
        <div class="flex-1">
            @php $group = $this->activeGroup; @endphp
            @if ($group)
                <form wire:submit.prevent="save" class="space-y-6 rounded-xl border border-gray-200 bg-white p-6 shadow-sm">
                    <h3 class="text-lg font-bold text-gray-900">{{ $group->label }}</h3>

                    <div class="grid grid-cols-1 gap-5 md:grid-cols-2">
                        @foreach ($group->fields as $field)
                            <div class="md:col-span-{{ $field->type === \Moe\Settings\Schema\SettingField::TYPE_TEXTAREA ? 2 : 1 }}">
                                <label class="mb-1 block text-sm font-semibold text-gray-700">
                                    {{ $field->label }}
                                </label>

                                @if ($field->type === \Moe\Settings\Schema\SettingField::TYPE_TOGGLE)
                                    <input type="checkbox" wire:model="values.{{ $field->key }}" class="rounded border-gray-300">

                                @elseif ($field->type === \Moe\Settings\Schema\SettingField::TYPE_TEXTAREA)
                                    <textarea wire:model="values.{{ $field->key }}" rows="3" placeholder="{{ $field->placeholder }}" class="w-full rounded-lg border border-gray-300 px-3 py-2 text-sm"></textarea>

                                @elseif ($field->type === \Moe\Settings\Schema\SettingField::TYPE_SELECT)
                                    <select wire:model="values.{{ $field->key }}" class="w-full rounded-lg border border-gray-300 px-3 py-2 text-sm">
                                        @foreach ($field->options as $optVal => $optLabel)
                                            <option value="{{ $optVal }}">{{ $optLabel }}</option>
                                        @endforeach
                                    </select>

                                @elseif ($field->type === \Moe\Settings\Schema\SettingField::TYPE_CHECKBOX_GROUP)
                                    <div class="flex flex-wrap gap-3">
                                        @foreach ($field->options as $optVal => $optLabel)
                                            <label class="flex items-center gap-2 rounded-lg border border-gray-300 px-3 py-2 text-sm">
                                                <input type="checkbox" wire:model="values.{{ $field->key }}" value="{{ $optVal }}" class="rounded border-gray-300">
                                                <span>{{ $optLabel }}</span>
                                            </label>
                                        @endforeach
                                    </div>

                                @elseif ($field->type === \Moe\Settings\Schema\SettingField::TYPE_PASSWORD)
                                    <input type="password" wire:model="values.{{ $field->key }}" placeholder="{{ $passwordMask[$field->key] ?? false ? '•••••••• (tidak diubah)' : $field->placeholder }}" class="w-full rounded-lg border border-gray-300 px-3 py-2 text-sm">

                                @elseif ($field->type === \Moe\Settings\Schema\SettingField::TYPE_IMAGE)
                                    @if (! empty($values[$field->key]))
                                        <img src="{{ asset($values[$field->key]) }}" alt="{{ $field->label }}" class="mb-2 h-20 w-20 rounded-lg border border-gray-200 object-cover">
                                    @endif
                                    <input type="file" wire:model="imageUploads.{{ $field->key }}" class="w-full rounded-lg border border-gray-300 px-3 py-2 text-sm">

                                @else
                                    <input
                                        type="{{ $field->type === \Moe\Settings\Schema\SettingField::TYPE_NUMBER ? 'number' : 'text' }}"
                                        wire:model="values.{{ $field->key }}"
                                        placeholder="{{ $field->placeholder }}"
                                        class="w-full rounded-lg border border-gray-300 px-3 py-2 text-sm"
                                    >
                                @endif

                                @if ($field->description)
                                    <p class="mt-1 text-xs text-gray-400">{{ $field->description }}</p>
                                @endif
                            </div>
                        @endforeach
                    </div>

                    <div class="pt-2">
                        <button type="submit" class="rounded-lg bg-primary px-6 py-2.5 text-sm font-semibold text-white hover:opacity-90">
                            Simpan Perubahan
                        </button>
                    </div>
                </form>
            @endif
        </div>
    </div>
</div>
