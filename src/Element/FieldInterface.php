<?php
/*
 * This file is part of the Form package.
 *
 * (c) Unit6 <team@unit6websites.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Unit6\Form\Element;

use Unit6\Form\Template;

/**
 * Field Interface
 *
 * This class provides a common interface to manage Elements
 */
interface FieldInterface
{
    /**
     * Print Field
     *
     * @param Template|null $template Template for elements
     *
     * @return void
     */
    public function render(Template $template = null);

    /**
     * Get Field Identifier
     *
     * @return string
     */
    public function getId();

    /**
     * Get Field Value
     *
     * @return string
     */
    public function getValue();
}