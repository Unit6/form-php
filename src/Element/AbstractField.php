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
use Unit6\Form\Validation;

/**
 * Abstract for Form Input
 *
 * Shared properties and methods for a standard form field.
 */
abstract class AbstractField extends AbstractElement implements FieldInterface
{
    /**
     * Field Name
     *
     * @var string
     */
    protected $name;

    /**
     * Field Label
     *
     * @var string
     */
    protected $label;

    /**
     * Field Form Identifier
     *
     * @var string
     */
    protected $formId;

    /**
     * Field Form Value
     *
     * @var string
     */
    protected $value;

    /**
     * Field Form Rules
     *
     * @var array
     */
    protected $rules;

    /**
     * Field Form Validation
     *
     * @var Validation
     */
    protected $validation;

    /**
     * Create a new field.
     *
     * @param array|null $params Any params of the field.
     *
     * @return void
     */
    public function __construct(array $params = null)
    {
        if (isset($params['rules'])) {
            $this->setValidation($params['rules']);
        }

        if (isset($params['attributes'])) {
            $this->setAttributes($params['attributes']);
        }
    }

    /**
     * Get Field Name
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Get Field Label
     *
     * @return string
     */
    public function getLabel()
    {
        return $this->label;
    }

    /**
     * Get Field Form Identifier
     *
     * @return string
     */
    public function getFormId()
    {
        return $this->formId;
    }

    /**
     * Set Field Form Identifier
     *
     * @param string $formId Associated Form Identifier
     *
     * @return void
     */
    public function setFormId($formId)
    {
        $this->formId = $formId;
    }

    /**
     * Generate Field Identifier
     *
     * @param string $formId Associated Form Identifier
     *
     * @return void
     */
    public function assignTo($formId)
    {
        $this->setFormId($formId);

        $this->id = Template::slug($this->getFormId() . '-' . $this->getName());
    }

    /**
     * Get Field Value
     *
     * @return string
     */
    public function getValue()
    {
        return $this->value;
    }

    /**
     * Set Field Value
     *
     * @param mixed $value Value of field
     *
     * @return void
     */
    public function setValue($value)
    {
        $this->value = $value;
    }

    /**
     * Set Validation Rules
     *
     * @param array $rules The validation rules for the element
     *
     * @return void
     */
    public function setValidation(array $rules)
    {
        $this->rules = $rules;

        $this->validation = new Validation($this);
    }

    /**
     * Get Validation
     *
     * @return Validation
     */
    public function getValidation()
    {
        return $this->validation;
    }

    /**
     * Get Field Validation Rules
     *
     * @return array
     */
    public function getRules()
    {
        return $this->rules;
    }

    /**
     * Print out input element.
     *
     * @param Template|null $template Template for elements
     *
     * @return void
     */
    public function render(Template $template = null)
    {
        if ($template instanceof Template) {
            $format = $template->forField($this->getTag(), $this->getType());
        }

        $format = ($format ?: static::$defaultFormat);

        $data = $this->getParameters();

        // If there is no value omit the attribute.
        if (empty($data['{value}']) && ! is_numeric($data['{value}'])) {
            $format = str_replace(' value="{value}"', '', $format);
        } else {
            $data['{value}'] = self::sanitize($data['{value}']);
        }

        // Parse attributes in format with designated attributes.
        if (empty($data['{attributes}'])) {
            $format = str_replace(' {attributes}', '', $format);
        } else {
            Template::formatAttributes($this->getTag(), $format, $this->attributes);
            $data['{attributes}'] = $this->getAttributesLine();
        }

        echo Template::merge($format, $data);
    }
}
