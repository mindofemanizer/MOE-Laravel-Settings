<?php

namespace Moe\Settings\Defaults;

use Moe\Settings\Schema\SettingField;
use Moe\Settings\Schema\SettingGroup;
use Moe\Settings\SettingDefinitions;

/**
 * Grup setting GENERIC yang sama di semua app MindOfEmanizer.
 * App cukup `composer require` + letakkan <livewire:settings-manager/>.
 * Field app-specific didaftarkan terpisah di app masing-masing.
 */
class DefaultSettingGroups
{
    public static function register(): void
    {
        // === GENERAL ===
        SettingDefinitions::register(new SettingGroup('general', 'Umum', 'store', [
            new SettingField('app_name', 'Nama Aplikasi', SettingField::TYPE_TEXT, 'My App', 'general', 'Nama aplikasi yang tampil di title/brand.'),
            new SettingField('app_description', 'Deskripsi', SettingField::TYPE_TEXTAREA, '', 'general', 'Deskripsi singkat aplikasi.'),
            new SettingField('app_logo', 'Logo', SettingField::TYPE_TEXT, '', 'general', 'Path logo (hasil upload image pipeline).'),
            new SettingField('app_favicon', 'Favicon', SettingField::TYPE_TEXT, '', 'general', 'Path favicon.'),
            new SettingField('contact_email', 'Email Kontak', SettingField::TYPE_TEXT, 'admin@example.com', 'general', 'Email kontak utama.'),
            new SettingField('contact_phone', 'Telepon / WA', SettingField::TYPE_TEXT, '', 'general', 'Nomor telepon/WhatsApp kontak.'),
            new SettingField('currency', 'Mata Uang', SettingField::TYPE_TEXT, 'IDR', 'general', 'Kode mata uang (ISO).'),
            new SettingField('maintenance_mode', 'Mode Maintenance', SettingField::TYPE_TOGGLE, false, 'general', 'Aktifkan untuk nonaktifkan akses publik.'),
            new SettingField('search_engine_indexing', 'Index Search Engine', SettingField::TYPE_TOGGLE, true, 'general', 'Izinkan Google/Bing mengindeks.'),
            new SettingField('timezone', 'Timezone', SettingField::TYPE_TEXT, 'Asia/Jakarta', 'general', 'Default timezone aplikasi.'),
            new SettingField('date_format', 'Format Tanggal', SettingField::TYPE_TEXT, 'd M Y', 'general', 'Format tanggal PHP.'),
            new SettingField('datetime_format', 'Format Tanggal & Waktu', SettingField::TYPE_TEXT, 'd M Y H:i', 'general', 'Format datetime PHP.'),
        ]));

        // === ADDRESS ===
        SettingDefinitions::register(new SettingGroup('address', 'Alamat', 'location_on', [
            new SettingField('address_street', 'Jalan', SettingField::TYPE_TEXT, '', 'address'),
            new SettingField('address_village', 'Desa/Kelurahan', SettingField::TYPE_TEXT, '', 'address'),
            new SettingField('address_district', 'Kecamatan', SettingField::TYPE_TEXT, '', 'address'),
            new SettingField('address_city', 'Kabupaten/Kota', SettingField::TYPE_TEXT, '', 'address'),
            new SettingField('address_province', 'Provinsi', SettingField::TYPE_TEXT, '', 'address'),
            new SettingField('address_postal', 'Kode Pos', SettingField::TYPE_TEXT, '', 'address'),
            new SettingField('address_lat', 'Latitude', SettingField::TYPE_TEXT, '', 'address'),
            new SettingField('address_lng', 'Longitude', SettingField::TYPE_TEXT, '', 'address'),
        ]));

        // === MAIL (SMTP) ===
        SettingDefinitions::register(new SettingGroup('mail', 'Email (SMTP)', 'mail', [
            new SettingField('mail_host', 'SMTP Host', SettingField::TYPE_TEXT, '', 'mail'),
            new SettingField('mail_port', 'SMTP Port', SettingField::TYPE_NUMBER, 587, 'mail'),
            new SettingField('mail_username', 'SMTP User', SettingField::TYPE_TEXT, '', 'mail'),
            new SettingField('mail_password', 'SMTP Password', SettingField::TYPE_PASSWORD, '', 'mail', description: null, sensitive: true),
            new SettingField('mail_encryption', 'Enkripsi', SettingField::TYPE_SELECT, 'tls', 'mail', null, ['tls' => 'TLS', 'ssl' => 'SSL', 'null' => 'None']),
            new SettingField('mail_from_address', 'From Address', SettingField::TYPE_TEXT, '', 'mail'),
            new SettingField('mail_from_name', 'From Name', SettingField::TYPE_TEXT, '', 'mail'),
        ]));

        // === STORAGE (R2 / local) ===
        SettingDefinitions::register(new SettingGroup('storage', 'Penyimpanan', 'cloud', [
            new SettingField('filesystem_default', 'Default Disk', SettingField::TYPE_SELECT, 'local', 'storage', null, ['local' => 'Lokal (Laravel)', 'r2' => 'Cloudflare R2']),
            new SettingField('r2_key', 'R2 Access Key', SettingField::TYPE_PASSWORD, '', 'storage', description: null, sensitive: true),
            new SettingField('r2_secret', 'R2 Secret Key', SettingField::TYPE_PASSWORD, '', 'storage', description: null, sensitive: true),
            new SettingField('r2_bucket', 'R2 Bucket', SettingField::TYPE_TEXT, '', 'storage'),
            new SettingField('r2_endpoint', 'R2 Endpoint', SettingField::TYPE_TEXT, '', 'storage'),
            new SettingField('r2_region', 'R2 Region', SettingField::TYPE_TEXT, 'auto', 'storage'),
        ]));

        // === SECURITY ===
        SettingDefinitions::register(new SettingGroup('security', 'Keamanan', 'shield', [
            new SettingField('max_login_attempts', 'Maks Percobaan Login', SettingField::TYPE_NUMBER, 5, 'security'),
            new SettingField('session_lifetime', 'Session Lifetime (menit)', SettingField::TYPE_NUMBER, 120, 'security'),
            new SettingField('two_factor_required', 'Wajib 2FA Admin', SettingField::TYPE_TOGGLE, false, 'security'),
        ]));

        // === NOTIFICATIONS ===
        SettingDefinitions::register(new SettingGroup('notifications', 'Notifikasi', 'notifications', [
            new SettingField('notif_new_user', 'User Baru', SettingField::TYPE_TOGGLE, true, 'notifications'),
            new SettingField('notif_payment', 'Pembayaran Masuk', SettingField::TYPE_TOGGLE, true, 'notifications'),
            new SettingField('notif_support', 'Tiket Support', SettingField::TYPE_TOGGLE, true, 'notifications'),
        ]));

        // === BACKUP ===
        SettingDefinitions::register(new SettingGroup('backup', 'Backup', 'backup', [
            new SettingField('backup_auto_enabled', 'Backup Otomatis', SettingField::TYPE_TOGGLE, true, 'backup'),
            new SettingField('backup_interval_hours', 'Interval (jam)', SettingField::TYPE_NUMBER, 24, 'backup'),
            new SettingField('backup_retention_days', 'Retensi (hari)', SettingField::TYPE_NUMBER, 30, 'backup'),
        ]));

        // === API ===
        SettingDefinitions::register(new SettingGroup('api', 'API', 'api', [
            new SettingField('api_rate_limit_per_minute', 'Rate Limit / menit', SettingField::TYPE_NUMBER, 60, 'api'),
            new SettingField('api_max_upload_size_mb', 'Max Upload (MB)', SettingField::TYPE_NUMBER, 10, 'api'),
            new SettingField('public_api_enabled', 'Public API', SettingField::TYPE_TOGGLE, true, 'api'),
        ]));

        // === PAYMENT ===
        // Key menggunakan dot-notation yang sama dengan legacy KiosKit
        // (pg.midtrans.*, pg.xendit.*, pg.manual.*) agar service app tidak berubah.
        SettingDefinitions::register(new SettingGroup('payment', 'Pembayaran', 'payments', [
            // Midtrans
            new SettingField('pg.midtrans.enabled', 'Midtrans Aktif', SettingField::TYPE_TOGGLE, false, 'payment'),
            new SettingField('pg.midtrans.mode', 'Midtrans Mode', SettingField::TYPE_SELECT, 'sandbox', 'payment', null, ['sandbox' => 'Sandbox', 'production' => 'Production']),
            new SettingField('pg.midtrans.client_key', 'Midtrans Client Key', SettingField::TYPE_TEXT, '', 'payment'),
            new SettingField('pg.midtrans.server_key', 'Midtrans Server Key', SettingField::TYPE_PASSWORD, '', 'payment', description: null, sensitive: true),
            new SettingField('pg.midtrans.channels', 'Midtrans Channel', SettingField::TYPE_CHECKBOX_GROUP, ['gopay', 'bank_transfer', 'credit_card'], 'payment', null, [
                'wallet' => 'Wallet', 'bank_transfer' => 'Transfer Bank', 'gopay' => 'GoPay', 'ovo' => 'OVO', 'credit_card' => 'Kartu Kredit', 'qris' => 'QRIS',
            ]),
            // Xendit
            new SettingField('pg.xendit.enabled', 'Xendit Aktif', SettingField::TYPE_TOGGLE, false, 'payment'),
            new SettingField('pg.xendit.mode', 'Xendit Mode', SettingField::TYPE_SELECT, 'sandbox', 'payment', null, ['sandbox' => 'Sandbox', 'production' => 'Production']),
            new SettingField('pg.xendit.api_key', 'Xendit API Key', SettingField::TYPE_PASSWORD, '', 'payment', description: null, sensitive: true),
            new SettingField('pg.xendit.webhook_token', 'Xendit Webhook Token', SettingField::TYPE_PASSWORD, '', 'payment', description: null, sensitive: true),
            new SettingField('pg.xendit.channels', 'Xendit Channel', SettingField::TYPE_CHECKBOX_GROUP, ['qris', 'bank_transfer', 'ovo'], 'payment', null, [
                'wallet' => 'Wallet', 'bank_transfer' => 'Transfer Bank', 'gopay' => 'GoPay', 'ovo' => 'OVO', 'credit_card' => 'Kartu Kredit', 'qris' => 'QRIS',
            ]),
            // Manual
            new SettingField('pg.manual.enabled', 'Manual Aktif', SettingField::TYPE_TOGGLE, true, 'payment'),
            new SettingField('pg.manual.channels', 'Manual Channel', SettingField::TYPE_CHECKBOX_GROUP, ['bank_transfer', 'wallet'], 'payment', null, [
                'wallet' => 'Wallet', 'bank_transfer' => 'Transfer Bank', 'gopay' => 'GoPay', 'ovo' => 'OVO', 'credit_card' => 'Kartu Kredit', 'qris' => 'QRIS',
            ]),
        ]));
    }
}
