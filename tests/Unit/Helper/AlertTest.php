<?php

/*
 * This file is part of Laravel Alert.
 *
 * (c) Vincent Klaiber <hello@vinkla.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace Tests\Unit\Helper;

use App\Helper\Alert;
use Tests\TestCase;

/**
 * This is the alert test class.
 *
 * @author Vincent Klaiber <hello@vinkla.com>
 */
class AlertTest extends TestCase
{
    public function testFlash()
    {
        $alert = $this->getAlert();

        $alert->flash('bart');
        $this->assertFlash('bart', 'info');
    }

    public function testError()
    {
        $alert = $this->getAlert();

        $alert->error('maggie');
        $this->assertFlash('maggie', 'danger');
    }

    public function testDanger()
    {
        $alert = $this->getAlert();

        $alert->danger('homer');
        $this->assertFlash('homer', 'danger');
    }

    public function testInfo()
    {
        $alert = $this->getAlert();

        $alert->info('lisa');
        $this->assertFlash('lisa', 'info');
    }

    public function testSuccess()
    {
        $alert = $this->getAlert();

        $alert->success('marge');
        $this->assertFlash('marge', 'success');
    }

    public function testWarning()
    {
        $alert = $this->getAlert();

        $alert->warning('bob');
        $this->assertFlash('bob', 'warning');
    }

    public function getAlert()
    {
        return new Alert($this->app['session.store']);
    }

    protected function assertFlash($message, $style)
    {
        $this->assertSame($message, $this->app->session->get('alert.message'));
        $this->assertSame($style, $this->app->session->get('alert.style'));
    }
}
