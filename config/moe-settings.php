<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Table name
    |--------------------------------------------------------------------------
    */
    'table' => 'settings',

    /*
    |--------------------------------------------------------------------------
    | Cache store
    |--------------------------------------------------------------------------
    | Set to null to use the default cache store.
    */
    'cache_store' => null,

    /*
    |--------------------------------------------------------------------------
    | Default settings
    |--------------------------------------------------------------------------
    | These are inserted by Setting::syncDefaults() when they don't exist yet.
    */
    'defaults' => [
        // 'app.name' => ['value' => 'My App', 'type' => 'string', 'group' => 'general', 'description' => 'Application name'],
    ],

    /*
    |--------------------------------------------------------------------------
    | Image upload (TYPE_IMAGE fields)
    |--------------------------------------------------------------------------
    */
    'image_disk' => 'public',
    'image_dir' => 'settings',
    'image_uploader' => null, // closure: fn(string $key, \Livewire\Features\SupportFileUploads\TemporaryUploadedFile $file): string

    /*
    |--------------------------------------------------------------------------
    | Activity / Audit log
    |--------------------------------------------------------------------------
    | Tabel tujuan untuk helper RecordsActivity dan event SettingsSaved.
    | App melisten Moe\Settings\Events\SettingsSaved lalu mencatat ke tabel ini.
    | Skema minimal: id, user_id, action, model, model_id, old_values(json),
    | new_values(json), ip_address, user_agent, timestamps.
    */
    'audit_log_table' => 'audit_logs',
];
