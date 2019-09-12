<?php

/*
 * This file is part of Laravel Alert.
 *
 * (c) Vincent Klaiber <hello@vinkla.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace App\Helper;

use Illuminate\Session\Store;

/**
 * This is the alert class.
 *
 * @author Vincent Klaiber <hello@vinkla.com>
 */
class Alert
{
    /**
     * The session storage instance.
     *
     * @var Store
     */
    protected $session;

    /**
     * Create a new alert instance.
     *
     * @param Store $session
     *
     * @return void
     */
    public function __construct(Store $session)
    {
        $this->session = $session;
    }

    /**
     * Flash an alert.
     *
     * @param string $message
     * @param string $style
     *
     * @return Alert
     */
    public function flash(string $message, string $style = 'info'): self
    {
        $this->session->flash('alert.message', $message);
        $this->session->flash('alert.style', $style);

        return $this;
    }

    /**
     * Flash a danger alert.
     *
     * @param string $message
     *
     * @return Alert
     */
    public function danger(string $message): self
    {
        return $this->flash($message, 'danger');
    }

    /**
     * Flash an error alert.
     *
     * @param string $message
     *
     * @return Alert
     */
    public function error(string $message): self
    {
        return $this->danger($message);
    }

    /**
     * Flash an info alert.
     *
     * @param string $message
     *
     * @return Alert
     */
    public function info(string $message): self
    {
        return $this->flash($message, 'info');
    }

    /**
     * Flash a success alert.
     *
     * @param string $message
     *
     * @return Alert
     */
    public function success(string $message): self
    {
        return $this->flash($message, 'success');
    }

    /**
     * Flash a warning alert.
     *
     * @param string $message
     *
     * @return Alert
     */
    public function warning(string $message): self
    {
        return $this->flash($message, 'warning');
    }
}
