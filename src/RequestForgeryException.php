<?php
/*
 * This file is part of the Form package.
 *
 * (c) Unit6 <team@unit6websites.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Unit6\Form;

use Exception;

/**
 * RequestForgeryException
 *
 *
 */
class RequestForgeryException extends Exception
{
    /**
     * Create new Request Forgery Exception
     *
     * @param string $message Message to be appended to exception.
     *
     * @return void
     */
    public function __construct($message)
    {
        parent::__construct('CSRF validation failed: ' . $message);
    }
}