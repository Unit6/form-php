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

use InvalidArgumentException;

use Unit6\Form\Template;

/**
 * Form Button Element
 *
 *
 */
class Button extends AbstractField
{
    /**
     * HTML Tag Name
     *
     * @var string
     */
    protected $tag = 'button';

     /**
     * Default format for element
     *
     * @var string
     */
    public static $defaultFormat = '<button type="{type}" id="{id}" name="{name}" value="{value}" {attributes}>{label}</button>';

    /**
     * Type of input
     *
     * @var string
     */
    protected $type;

    /**
     * Value attribute for element
     *
     * @var string
     */
    public static $typeOptions = [
        'submit',
        'reset',
        'button'
    ];

    /**
     * Create a new element.
     *
     * @param string      $type       The type of input
     * @param string      $name       The name attribute of input
     * @param string      $label      The text content for label
     * @param string|null $value      The value attribute of input
     * @param array|null  $params Any remaining params of input
     *
     * @return void
     */
    public function __construct($type, $name, $label, $value = null, array $params = null)
    {
        if ( ! in_array($type, static::$typeOptions)) {
            throw new InvalidArgumentException(sprintf('Unsupported button type "%s" provided', $type));
        }

        $this->type = $type;
        $this->name = $name;
        $this->label = $label;
        $this->value = $value;

        parent::__construct($params);
    }

    /**
     * Get Field Value
     *
     * @return string
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * Get Field Parameters
     *
     * @return string
     */
    public function getParameters()
    {
        return [
            '{id}'         => $this->getId(),
            '{label}'      => $this->getLabel(),
            '{type}'       => $this->getType(),
            '{name}'       => $this->getName(),
            '{value}'      => $this->getValue(),
            '{attributes}' => $this->getAttributes()
        ];
    }
}
