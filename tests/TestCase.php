<?php

namespace Tests;

use App\Models\Setting;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;

abstract class TestCase extends BaseTestCase
{
    use CreatesApplication;

    protected function setUp(): void
    {
        parent::setUp();

        Setting::withoutEvents(function () {
            Setting::updateOrCreate(
                ['key' => 'system_setup_completed'],
                ['key' => 'system_setup_completed', 'value' => true]
            );
        });
    }
}
