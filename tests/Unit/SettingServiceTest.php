<?php

namespace Moe\Settings\Tests\Unit;

use Moe\Settings\Facades\Setting;
use Moe\Settings\Models\Setting as SettingModel;
use Moe\Settings\Tests\TestCase;

class SettingServiceTest extends TestCase
{
    public function test_set_and_get_string(): void
    {
        Setting::set('app.name', 'KiosKit', 'string');

        $this->assertEquals('KiosKit', Setting::get('app.name'));
    }

    public function test_set_and_get_integer(): void
    {
        Setting::set('pagination.per_page', 25, 'integer');

        $this->assertSame(25, Setting::get('pagination.per_page'));
        $this->assertIsInt(Setting::get('pagination.per_page'));
    }

    public function test_set_and_get_boolean(): void
    {
        Setting::set('feature.x', true, 'boolean');

        $this->assertTrue(Setting::get('feature.x'));
        $this->assertIsBool(Setting::get('feature.x'));
    }

    public function test_set_and_get_float(): void
    {
        Setting::set('tax.rate', 0.11, 'float');

        $this->assertEquals(0.11, Setting::get('tax.rate'));
        $this->assertIsFloat(Setting::get('tax.rate'));
    }

    public function test_set_and_get_json(): void
    {
        $value = ['whatsapp' => '62812', 'email' => 'a@b.com'];
        Setting::set('contact', $value, 'json');

        $this->assertSame($value, Setting::get('contact'));
        $this->assertIsArray(Setting::get('contact'));
    }

    public function test_set_and_get_encrypted(): void
    {
        Setting::set('api.secret', 'sk-secret-123', 'encrypted');

        $this->assertSame('sk-secret-123', Setting::get('api.secret'));

        $row = SettingModel::query()->where('key', 'api.secret')->first();
        $this->assertNotNull($row);
        $this->assertNotSame('sk-secret-123', $row->value);
    }

    public function test_plaintext_tolerance_on_encrypted(): void
    {
        SettingModel::query()->create([
            'key' => 'legacy.key',
            'value' => 'still-plaintext',
            'type' => 'encrypted',
        ]);

        $this->assertSame('still-plaintext', Setting::get('legacy.key'));
    }

    public function test_get_returns_default_when_missing(): void
    {
        $this->assertSame('fallback', Setting::get('nonexistent', 'fallback'));
        $this->assertNull(Setting::get('nonexistent'));
    }

    public function test_has(): void
    {
        Setting::set('exists', 'yes', 'string');

        $this->assertTrue(Setting::has('exists'));
        $this->assertFalse(Setting::has('does-not-exist'));
    }

    public function test_forget(): void
    {
        Setting::set('temp', 'value', 'string');
        $this->assertTrue(Setting::has('temp'));

        Setting::forget('temp');
        $this->assertFalse(Setting::has('temp'));
        $this->assertNull(Setting::get('temp'));
    }

    public function test_get_group(): void
    {
        Setting::set('mail.driver', 'smtp', 'string', 'mail');
        Setting::set('mail.port', 587, 'integer', 'mail');
        Setting::set('mail.encryption', 'tls', 'string', 'mail');
        Setting::set('unrelated', 'x', 'string', 'other');

        $group = Setting::getGroup('mail');

        $this->assertCount(3, $group);
        $this->assertSame('smtp', $group['mail.driver']);
        $this->assertSame(587, $group['mail.port']);
        $this->assertSame('tls', $group['mail.encryption']);
    }

    public function test_sync_defaults(): void
    {
        $defaults = [
            'app.name' => ['value' => 'My App', 'type' => 'string', 'group' => 'general'],
            'app.lang' => ['value' => 'id', 'type' => 'string', 'group' => 'general'],
            'pagination.per_page' => ['value' => 15, 'type' => 'integer', 'group' => 'general'],
        ];

        Setting::set('app.name', 'Custom', 'string');

        $inserted = Setting::syncDefaults($defaults);

        $this->assertSame(2, $inserted);
        $this->assertSame('Custom', Setting::get('app.name'));
        $this->assertSame('id', Setting::get('app.lang'));
        $this->assertSame(15, Setting::get('pagination.per_page'));
    }

    public function test_infer_types_from_php_values(): void
    {
        Setting::set('bool', true);
        Setting::set('int', 42);
        Setting::set('float', 3.14);
        Setting::set('arr', ['a' => 1]);
        Setting::set('str', 'hello');

        $this->assertTrue(Setting::get('bool'));
        $this->assertSame(42, Setting::get('int'));
        $this->assertEquals(3.14, Setting::get('float'));
        $this->assertSame(['a' => 1], Setting::get('arr'));
        $this->assertSame('hello', Setting::get('str'));
    }
}
