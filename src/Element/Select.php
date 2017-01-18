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
 * Form Select Field
 *
 *
 */
class Select extends AbstractField
{
    /**
     * HTML Tag Name
     *
     * @var string
     */
    protected $tag = 'select';

     /**
     * Default format for element
     *
     * @var string
     */
    public static $defaultFormat = '<label for="{id}">{label}</label><select id="{id}" name="{name}" {attributes}>{options}</select>';

    /**
     * Create a new element.
     *
     * @param string      $name       The name attribute of input
     * @param string      $label      The text content for label
     * @param string|null $value      The value attribute of input
     * @param array       $options    The select options
     * @param array|null  $params     Any remaining params of input
     *
     * @return void
     */
    public function __construct($name, $label, $value = null, array $options = [], array $params = null)
    {
        $this->name = $name;
        $this->label = $label;
        $this->value = $value;

        $this->setOptions($options);

        parent::__construct($params);
    }

    /**
     * Set Options
     *
     * Set the select options.
     *
     * @param array $options The select options
     *
     * @return void
     */
    public function setOptions(array $options)
    {
        $pieces = [];

        foreach ($options as $row) {
            if (isset($row['group'])) {
                $pieces[] = $this->buildOptionGroup($row);
            } else {
                $pieces[] = $this->buildOption($row);
            }
        }

        $this->options = implode('', $pieces);
    }

    /**
     * Get Element Options
     *
     * @return string
     */
    public function getOptions()
    {
        return $this->options;
    }

    /**
     * Build Option
     *
     * Build the string for option values
     *
     * @param array $options The select options
     *
     * @return string
     */
    public function buildOption($option)
    {
        $value = (isset($option['value']) ? $option['value'] : $option['label']);

        $selected = ($this->value === $value ? 'selected="selected" ' : '');

        return sprintf('<option %svalue="%s">%s</option>', $selected, $value, $option['label']);
    }

    /**
     * Build Option Group
     *
     * Build the string for an option group
     *
     * @param array $optgroup Optgroup options
     *
     * @return string
     */
    public function buildOptionGroup($optgroup)
    {
        $pieces = [];

        $attributes = [];

        if (isset($optgroup['disabled']) && $optgroup['disabled']) {
            $attributes['disabled'] = 'disabled';
        }

        $attributes = self::parseAttributesLine($attributes);

        $pieces[] = sprintf('<optgroup %slabel="%s">', $attributes, $optgroup['label']);

        foreach ($optgroup['group'] as $row) {
            $pieces[] = $this->buildOption($row);
        }

        $pieces[] = '</optgroup>';

        return implode('', $pieces);
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
            '{options}'    => $this->getOptions(),
            '{attributes}' => $this->getAttributes()
        ];
    }
}
