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
 * ValidationException
 *
 *
 */
class ValidationException extends Exception
{
    /**
     * Field element
     *
     * @var FieldInterface
     */
    protected $field;

    /**
     * Field rule name
     *
     * @var string
     */
    protected $ruleName;

    /**
     * Create new Validation Exception
     *
     * @param FieldInterface $field Field element being validated.
     * @param string         $rule  Field validation rule name.
     *
     * @return void
     */
    public function __construct(Element\FieldInterface $field, $ruleName)
    {
        $this->field = $field;
        $this->ruleName = $ruleName;

        $message = sprintf('Validation for field "%s" failed rule "%s"', $field->getLabel(), $ruleName);

        parent::__construct($message);
    }

    /**
     * Get Field
     *
     * @return FieldInterface
     */
    public function getField()
    {
        return $this->field;
    }

    /**
     * Get Rule name
     *
     * @return string
     */
    public function getRuleName()
    {
        return $this->ruleName;
    }
}