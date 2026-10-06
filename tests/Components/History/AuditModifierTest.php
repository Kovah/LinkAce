<?php

namespace Tests\Components\History;

use App\Audits\Modifiers\DarkmodeSettingModifier;
use App\Audits\Modifiers\DisplayModeSettingModifier;
use App\Audits\Modifiers\LocaleSettingModifier;
use App\Audits\Modifiers\VisibilityModifier;
use Tests\TestCase;

/**
 * Audit rows are persisted forever and there is no UI to delete them, so a
 * modifier must never throw on a value it does not know. Otherwise a single
 * stored value permanently breaks the audit log for all administrators.
 */
class AuditModifierTest extends TestCase
{
    public function test_locale_modifier_handles_unknown_locale(): void
    {
        $modifier = new LocaleSettingModifier();

        $this->assertEquals('English', $modifier->modify('en_US'));
        $this->assertEquals('zz_ZZ', $modifier->modify('zz_ZZ'));
        $this->assertNull($modifier->modify(null));
    }

    public function test_darkmode_modifier_handles_unknown_value(): void
    {
        $modifier = new DarkmodeSettingModifier();

        $this->assertEquals('Disabled', $modifier->modify(0));
        $this->assertEquals('99', $modifier->modify(99));
        $this->assertNull($modifier->modify(null));
    }

    public function test_display_mode_modifier_handles_unknown_value(): void
    {
        $modifier = new DisplayModeSettingModifier();

        $this->assertEquals('Display Links as simple List', $modifier->modify(2));
        $this->assertEquals('99', $modifier->modify(99));
        $this->assertNull($modifier->modify(null));
    }

    public function test_visibility_modifier_handles_unknown_value(): void
    {
        $modifier = new VisibilityModifier();

        $this->assertEquals('Public', $modifier->modify(1));
        // Values may be stored as strings, which must still resolve correctly
        $this->assertEquals('Public', $modifier->modify('1'));
        $this->assertEquals('99', $modifier->modify(99));
        $this->assertNull($modifier->modify(null));
    }
}
