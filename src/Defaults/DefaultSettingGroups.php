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
            new SettingField('auto_confirm_orders', 'Konfirmasi Otomatis Pesanan', SettingField::TYPE_TOGGLE, true, 'general', 'Konfirmasi pesanan otomatis setelah pembayaran.'),
            new SettingField('search_engine_enabled', 'Mesin Pencari Aktif', SettingField::TYPE_TOGGLE, true, 'general', 'Tampilkan kolom pencarian di toko.'),
            new SettingField('search_engine_indexing', 'Index Search Engine', SettingField::TYPE_TOGGLE, true, 'general', 'Izinkan Google/Bing mengindeks.'),
            new SettingField('timezone', 'Timezone', SettingField::TYPE_SELECT, 'Asia/Jakarta', 'general', 'Zona waktu operasional.', [
                'Asia/Jakarta' => 'Asia/Jakarta (WIB, UTC+7)',
                'Asia/Makassar' => 'Asia/Makassar (WITA, UTC+8)',
                'Asia/Jayapura' => 'Asia/Jayapura (WIT, UTC+9)',
                'Asia/Singapore' => 'Asia/Singapore (UTC+8)',
                'Asia/Kuala_Lumpur' => 'Asia/Kuala_Lumpur (UTC+8)',
                'UTC' => 'UTC',
            ]),
            new SettingField('date_format', 'Format Tanggal', SettingField::TYPE_SELECT, 'd M Y', 'general', 'Format tanggal PHP (helper format_setting_date).', [
                'd M Y' => '19 Jul 2026 (d M Y)',
                'd/m/Y' => '19/07/2026 (d/m/Y)',
                'd-m-Y' => '19-07-2026 (d-m-Y)',
                'Y-m-d' => '2026-07-19 (Y-m-d)',
                'd F Y' => '19 Juli 2026 (d F Y)',
            ]),
            new SettingField('datetime_format', 'Format Tanggal & Waktu', SettingField::TYPE_SELECT, 'd M Y H:i', 'general', 'Format datetime PHP (helper format_setting_date).', [
                'd M Y H:i' => '19 Jul 2026 15:30 (d M Y H:i)',
                'd/m/Y H:i' => '19/07/2026 15:30 (d/m/Y H:i)',
                'd-m-Y H:i' => '19-07-2026 15:30 (d-m-Y H:i)',
                'Y-m-d H:i' => '2026-07-19 15:30 (Y-m-d H:i)',
                'd M Y H:i:s' => '19 Jul 2026 15:30:00 (d M Y H:i:s)',
            ]),
        ]));

        // === ADDRESS (struktur Indonesia) ===
        SettingDefinitions::register(new SettingGroup('address', 'Alamat', 'location_on', [
            new SettingField('address_street', 'Nama Jalan', SettingField::TYPE_TEXT, '', 'address', 'Nama jalan/gang saja (tanpa nomor rumah).'),
            new SettingField('address_house_no', 'No. Rumah / Blok', SettingField::TYPE_TEXT, '', 'address', 'Nomor rumah, blok, atau unit.'),
            new SettingField('address_rt', 'RT', SettingField::TYPE_TEXT, '', 'address', 'Hanya angka 1–3 digit.'),
            new SettingField('address_rw', 'RW', SettingField::TYPE_TEXT, '', 'address', 'Hanya angka 1–3 digit.'),
            new SettingField('address_hamlet', 'Dusun / Kampung', SettingField::TYPE_TEXT, '', 'address', 'Nama dusun/kampung/lingkungan (opsional).'),
            new SettingField('address_landmark', 'Patokan / Catatan Kurir', SettingField::TYPE_TEXTAREA, '', 'address', 'Patokan yang memudahkan kurir (seberang masjid, dll).'),
            new SettingField('address_village', 'Desa / Kelurahan', SettingField::TYPE_TEXT, '', 'address', 'Nama desa/kelurahan.'),
            new SettingField('address_district', 'Kecamatan', SettingField::TYPE_TEXT, '', 'address', 'Nama kecamatan.'),
            new SettingField('address_city', 'Kabupaten / Kota', SettingField::TYPE_TEXT, '', 'address', 'Nama kabupaten atau kota.'),
            new SettingField('address_province', 'Provinsi', SettingField::TYPE_TEXT, '', 'address', 'Nama provinsi.'),
            new SettingField('address_postal_code', 'Kode Pos', SettingField::TYPE_TEXT, '', 'address', '5 digit kode pos.'),
            new SettingField('address_lat', 'Latitude', SettingField::TYPE_TEXT, '', 'address', 'Koordinat lintang (dari peta).'),
            new SettingField('address_lng', 'Longitude', SettingField::TYPE_TEXT, '', 'address', 'Koordinat bujur (dari peta).'),
        ]));

        // === MAIL (SMTP) ===
        SettingDefinitions::register(new SettingGroup('mail', 'Email (SMTP)', 'mail', [
            new SettingField('mail_provider', 'Provider Email', SettingField::TYPE_SELECT, 'log', 'mail',
                'Pilih provider untuk mengisi host/port/enkripsi otomatis (opsional). Kredensial tetap ditempel manual.',
                ['log' => 'Log (development)', 'brevo' => 'Brevo (Sendinblue)', 'mailgun' => 'Mailgun', 'resend' => 'Resend', 'postmark' => 'Postmark', 'sendgrid' => 'SendGrid', 'gmail' => 'Gmail SMTP', 'outlook' => 'Outlook SMTP', 'amazon_ses' => 'Amazon SES', 'custom' => 'Custom'],
            ),
            new SettingField('mail_mailer', 'Driver Mail', SettingField::TYPE_SELECT, 'log', 'mail',
                'log = development (tulis ke file). smtp = server SMTP. array = testing memori.',
                ['log' => 'Log (development)', 'smtp' => 'SMTP', 'array' => 'Array (testing)', 'sendmail' => 'Sendmail'],
            ),
            new SettingField('mail_host', 'SMTP Host', SettingField::TYPE_TEXT, '', 'mail', 'Hostname server SMTP.'),
            new SettingField('mail_port', 'SMTP Port', SettingField::TYPE_NUMBER, 587, 'mail', 'Port SMTP (587 STARTTLS, 465 SSL).'),
            new SettingField('mail_username', 'SMTP User', SettingField::TYPE_TEXT, '', 'mail', 'Username SMTP atau API key login.'),
            new SettingField('mail_password', 'SMTP Password / Key', SettingField::TYPE_PASSWORD, '', 'mail', 'Password SMTP / app password. Disimpan terenkripsi.', sensitive: true),
            new SettingField('mail_encryption', 'Enkripsi', SettingField::TYPE_SELECT, 'tls', 'mail', 'Metode enkripsi koneksi SMTP.', ['tls' => 'TLS (STARTTLS)', 'ssl' => 'SSL (SMTPS)', 'null' => 'None']),
            new SettingField('mail_from_address', 'From Address', SettingField::TYPE_TEXT, '', 'mail', 'Alamat pengirim global (header From).'),
            new SettingField('mail_from_name', 'From Name', SettingField::TYPE_TEXT, '', 'mail', 'Nama pengirim di inbox penerima.'),
        ]));

        // === STORAGE (local / public / R2) ===
        SettingDefinitions::register(new SettingGroup('storage', 'Penyimpanan', 'cloud', [
            new SettingField('filesystem_default', 'Default Disk', SettingField::TYPE_SELECT, 'local', 'storage',
                'Disk default untuk upload file. local = storage/app (dev). public = storage/app/public (+ storage:link). r2 = Cloudflare R2.',
                ['local' => 'Lokal (Laravel)', 'public' => 'Lokal publik (storage/app/public)', 'r2' => 'Cloudflare R2'],
            ),
            new SettingField('r2_key', 'R2 Access Key', SettingField::TYPE_PASSWORD, '', 'storage',
                'Access Key ID dari Cloudflare R2 API Tokens.', sensitive: true),
            new SettingField('r2_secret', 'R2 Secret Key', SettingField::TYPE_PASSWORD, '', 'storage',
                'Secret Access Key dari Cloudflare R2 API Tokens.', sensitive: true),
            new SettingField('r2_bucket', 'R2 Bucket', SettingField::TYPE_TEXT, '', 'storage',
                'Nama bucket di Cloudflare R2.'),
            new SettingField('r2_endpoint', 'R2 Endpoint', SettingField::TYPE_TEXT, '', 'storage',
                'S3-compatible endpoint URL (https://<account>.r2.cloudflarestorage.com).'),
            new SettingField('r2_region', 'R2 Region', SettingField::TYPE_TEXT, 'auto', 'storage',
                'Region bucket. Biarkan auto untuk R2.'),
            new SettingField('r2_url', 'R2 Public URL', SettingField::TYPE_TEXT, '', 'storage',
                'Public bucket URL (custom domain) agar file bisa diakses via browser. Opsional.'),
        ]));

        // === SECURITY (comprehensive) ===
        SettingDefinitions::register(new SettingGroup('security', 'Keamanan', 'shield', [
            // ── 1. Session & Autentikasi ──
            new SettingField('max_login_attempts', 'Maks Login Gagal', SettingField::TYPE_NUMBER, 5, 'security',
                'Jumlah maks percobaan login sebelum akun dikunci sementara.'),
            new SettingField('lockout_duration', 'Durasi Lockout (menit)', SettingField::TYPE_NUMBER, 10, 'security',
                'Berapa menit akun terkunci setelah melebihi maks login gagal.'),
            new SettingField('session_lifetime', 'Session Lifetime (menit)', SettingField::TYPE_NUMBER, 120, 'security',
                'Durasi session login admin/vendor/seller.'),
            new SettingField('session_idle_timeout', 'Idle Timeout (menit)', SettingField::TYPE_NUMBER, 30, 'security',
                'Auto logout jika tidak ada aktivitas. 0 = nonaktif.'),
            new SettingField('single_device_session', 'Single Device Session', SettingField::TYPE_TOGGLE, false, 'security',
                'Logout otomatis dari perangkat lain saat login baru.'),
            new SettingField('require_email_verification', 'Wajib Verifikasi Email', SettingField::TYPE_TOGGLE, true, 'security',
                'Akun baru hanya bisa bertransaksi setelah verifikasi email.'),

            // ── 2. Kebijakan Password ──
            new SettingField('password_min_length', 'Min. Panjang Password', SettingField::TYPE_NUMBER, 8, 'security',
                'Minimal karakter password. Rekomendasi: 8–12.'),
            new SettingField('password_require_uppercase', 'Harus Huruf Besar', SettingField::TYPE_TOGGLE, true, 'security',
                'Password wajib mengandung minimal 1 huruf besar (A-Z).'),
            new SettingField('password_require_digit', 'Harus Angka', SettingField::TYPE_TOGGLE, true, 'security',
                'Password wajib mengandung minimal 1 angka (0-9).'),
            new SettingField('password_require_symbol', 'Harus Simbol', SettingField::TYPE_TOGGLE, true, 'security',
                'Password wajib mengandung minimal 1 simbol (!@#$% dll).'),
            new SettingField('password_expiry_days', 'Expiry Password (hari)', SettingField::TYPE_NUMBER, 0, 'security',
                'Paksa ganti password setiap N hari. 0 = tidak pernah expired.'),

            // ── 3. Two-Factor Authentication ──
            new SettingField('two_factor_admin', 'Wajib 2FA Admin', SettingField::TYPE_TOGGLE, false, 'security',
                'Admin harus setup Google Authenticator / TOTP sebelum akses.'),
            new SettingField('two_factor_vendor', 'Wajib 2FA Vendor', SettingField::TYPE_TOGGLE, false, 'security',
                'Vendor harus setup TOTP sebelum akses portal vendor.'),
            new SettingField('two_factor_seller', 'Wajib 2FA Seller', SettingField::TYPE_TOGGLE, false, 'security',
                'Seller harus setup TOTP sebelum akses dashboard toko.'),
            new SettingField('two_factor_remember_days', 'Ingat Device 2FA (hari)', SettingField::TYPE_NUMBER, 30, 'security',
                'Browser tepercaya tidak minta kode 2FA lagi selama N hari.'),

            // ── 4. reCAPTCHA ──
            new SettingField('recaptcha_enabled', 'Aktifkan reCAPTCHA', SettingField::TYPE_TOGGLE, true, 'security',
                'Proteksi form publik dengan Google reCAPTCHA v2.'),
            new SettingField('recaptcha_site_key', 'Site Key (reCAPTCHA)', SettingField::TYPE_TEXT, '', 'security',
                'Site Key dari Google reCAPTCHA admin console.'),
            new SettingField('recaptcha_secret_key', 'Secret Key (reCAPTCHA)', SettingField::TYPE_PASSWORD, '', 'security',
                'Secret Key dari Google reCAPTCHA admin console.', sensitive: true),
            new SettingField('recaptcha_for_login', 'reCAPTCHA di Login', SettingField::TYPE_TOGGLE, true, 'security',
                'Terapkan captcha di form login admin & store.'),
            new SettingField('recaptcha_for_register', 'reCAPTCHA di Register', SettingField::TYPE_TOGGLE, true, 'security',
                'Terapkan captcha di form pendaftaran seller/vendor.'),
            new SettingField('recaptcha_threshold', 'Threshold Captcha', SettingField::TYPE_NUMBER, 3, 'security',
                'Jumlah percobaan gagal sebelum captcha muncul. 1 = selalu captcha.'),

            // ── 5. Pendaftaran & Approval ──
            new SettingField('registration_enabled', 'Izinkan Pendaftaran', SettingField::TYPE_TOGGLE, true, 'security',
                'Publik bisa mendaftar sebagai seller/vendor.'),
            new SettingField('admin_approval_required', 'Perlu Approval Admin', SettingField::TYPE_TOGGLE, false, 'security',
                'Akun seller/vendor baru harus di-approve admin sebelum aktif.'),

            // ── 6. Upload Security ──
            new SettingField('max_upload_file_size', 'Max Upload File (MB)', SettingField::TYPE_NUMBER, 10, 'security',
                'Batas maksimal ukuran file upload (produk, banner, logo).'),
            new SettingField('blocked_file_extensions', 'Ekstensi Diblokir', SettingField::TYPE_TEXT, 'exe,php,js,sh,bat,com,dll,scr,msi', 'security',
                'Ekstensi file yang tidak diizinkan diupload (pisah koma).'),

            // ── 7. Audit & IP ──
            new SettingField('audit_log_retention_days', 'Retensi Log Aktivitas (hari)', SettingField::TYPE_NUMBER, 90, 'security',
                'Hapus otomatis log aktivitas lebih dari N hari. 0 = simpan selamanya.'),
            new SettingField('admin_ip_whitelist_enabled', 'Aktifkan IP Whitelist Admin', SettingField::TYPE_TOGGLE, false, 'security',
                'Batasi akses admin hanya dari IP tertentu.'),
            new SettingField('admin_ip_whitelist', 'IP Whitelist Admin', SettingField::TYPE_TEXTAREA, '', 'security',
                'Alamat IP yang diizinkan (satu IP per baris). Kosong = semua IP ditolak saat fitur aktif.'),
            new SettingField('sanctum_token_expiration', 'Expiry Token API (hari)', SettingField::TYPE_NUMBER, 0, 'security',
                'Sanctum token API otomatis expired. 0 = tidak pernah (perhatikan risiko keamanan).'),
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
