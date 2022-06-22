<?php

namespace Tests;

use App\Settings\SystemSettings;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;

abstract class TestCase extends BaseTestCase
{
    use CreatesApplication;

    protected function setUp(): void
    {
        parent::setUp();

        SystemSettings::fake([
            'setup_completed' => true
        ]);
    }
}
