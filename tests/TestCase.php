<?php

namespace Tests;

use App\Settings\SystemSettings;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;
use Illuminate\Support\Facades\Schema;

abstract class TestCase extends BaseTestCase
{
    use CreatesApplication;

    protected function setUp(): void
    {
        parent::setUp();

        if (Schema::hasTable('settings')) {
            SystemSettings::fake([
                'setup_completed' => true,
            ]);
        }
    }
}
