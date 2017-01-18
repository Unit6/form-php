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
 * Form Textarea Field
 *
 *
 */
class Textarea extends AbstractField
{
    /**
     * HTML Tag Name
     *
     * @var string
     */
    protected $tag = 'textarea';

     /**
     * Default format for element
     *
     * @var string
     */
    public static $defaultFormat = '<label for="{id}">{label}</label><textarea id="{id}" name="{name}" {attributes}>{value}</textarea>';

    /**
     * Create a new element.
     *
     * @param string      $name       The name attribute of textarea
     * @param string      $label      The text content for label
     * @param string|null $value      The content of textarea
     * @param array|null  $params     Any remaining params of textarea
     *
     * @return void
     */
    public function __construct($name, $label, $value = null, array $params = null)
    {
        $this->name = $name;
        $this->label = $label;
        $this->value = $value;

        parent::__construct($params);
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
            '{name}'       => $this->getName(),
            '{value}'      => $this->getValue(),
            '{attributes}' => $this->getAttributes()
        ];
    }
}
