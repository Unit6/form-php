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
 * Form Input Field
 *
 *
 */
class Input extends AbstractField
{
    /**
     * HTML Tag Name
     *
     * @var string
     */
    protected $tag = 'input';

     /**
     * Default format for element
     *
     * @var string
     */
    public static $defaultFormat = '<label for="{id}">{label}</label><input type="{type}" id="{id}" name="{name}" value="{value}" {attributes}>{datalist}';

    /**
     * Type of input
     *
     * @var string
     */
    protected $type;

    /**
     * Datalist options
     *
     * @var array
     */
    protected $options;

    /**
     * Value attribute for element
     *
     * @var string
     */
    public static $typeOptions = [
        'button',
        'checkbox',
        'color',
        'date',
        'datetime',
        'datetime-local',
        'email',
        'file',
        'hidden',
        'image',
        'month',
        'number',
        'password',
        'radio',
        'range',
        'reset',
        'search',
        'submit',
        'tel',
        'text',
        'time',
        'url',
        'week'
    ];

    /**
     * Create a new element.
     *
     * @param string      $type       The type of input
     * @param string      $name       The name attribute of input
     * @param string      $label      The text content for label
     * @param string|null $value      The value attribute of input
     * @param array|null  $params     Any remaining params of input
     *
     * @return void
     */
    public function __construct($type, $name, $label, $value = null, array $params = null)
    {
        if ( ! in_array($type, static::$typeOptions)) {
            throw new InvalidArgumentException(sprintf('Unsupported input type "%s" provided', $type));
        }

        if (isset($params['list'])) {
            $this->options = $params['list'];
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
     * Get List
     *
     * Get the datalist options.
     *
     * @param array $options The datalist options
     *
     * @return string
     */
    public function getList()
    {
        $pieces = [];

        if ($this->options) {
            $id = $this->getId() . '-list';

            $this->attributes['list'] = $id;

            $pieces[] = sprintf('<datalist id="%s">', $id);

            foreach ($this->options as $row) {
                $pieces[] = sprintf('<option value="%s">', $row);
            }

            $pieces[] = '</datalist>';
        }

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
            '{datalist}'   => $this->getList(),
            '{attributes}' => $this->getAttributes()
        ];
    }
}
