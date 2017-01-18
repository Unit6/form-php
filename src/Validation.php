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

use InvalidArgumentException;
use UnexpectedValueException;

/**
 * Form Validation
 *
 *
 */
class Validation
{
    /**
     * Field element
     *
     * @var FieldInterface
     */
    protected $field;

    /**
     * Is field value required
     *
     * @var boolean
     */
    protected $isRequired;

    /**
     * Field Rules
     *
     * Whitelisted and verified rules to run.
     *
     * @var array
     */
    protected $rules = [];

    /**
     * Allowed validation rules
     *
     * @var array
     */
    public static $supportedRules = [
        'Alpha',
        'AlphaNum',
        'Between',
        'Email',
        'Equals',
        'Float',
        'Integer',
        'Length',
        'Max',
        'MaxLength',
        'Min',
        'MinLength',
        'Numeric',
        'Required',
    ];

    /**
     * Create new validation instance
     *
     * @param FieldInterface $field Field element being validated.
     *
     * @return void
     */
    public function __construct(Element\FieldInterface $field)
    {
        $rules = $field->getRules();

        if ( ! $rules) {
            throw new UnexpectedValueException('Validation rules have not been provided');
        }

        foreach ($rules as $key => $rule) {
            if (is_callable($rule)) {
                $this->rules[$key] = $rule;
            } else {
                $this->parseRule($rule);
                $this->rules[$rule['name']] = $rule;
            }
        }

        $this->field = $field;
        $this->isRequired = isset($this->rules['Required']);

        if ($this->isRequired) {
            unset($this->rules['Required']);
        }
    }

    /**
     * Execute validation
     *
     * @return void
     */
    public function __invoke()
    {
        $this->validate();
    }

    /**
     * Validate form element
     *
     * @return void
     */
    private function validate()
    {
        $value = $this->getField()->getValue();

        // Throw exception if value is required and empty
        // Other rules should not be run if the field is empty
        if ($this->checkBlank($value)) {
            if ($this->isRequired) {
                throw new ValidationException($this->getField(), 'Required');
            }
            return;
        }

        foreach ($this->getRules() as $key => $rule) {
            if (is_callable($rule)) {

                // Run custom function
                $result = call_user_func($rule, $value);

            } else {

                if ( ! is_callable([$this, $rule['method']])) {
                    throw new InvalidArgumentException(sprintf('Rule method does not exist: "%s"', $rule['method']));
                }

                // Pass $value as first argument to requested validation method
                array_unshift($rule['arguments'], $value);
                $result = call_user_func_array([$this, $rule['method']], $rule['arguments']);

            }

            if ( ! $result) {
                throw new ValidationException($this->getField(), $key);
            }
        }
    }

    /**
     * Get field
     *
     * @return Element\FieldInterface
     */
    public function getField()
    {
        return $this->field;
    }

    /**
     * Get validation rules
     *
     * @return array
     */
    public function getRules()
    {
        return $this->rules;
    }

    /**
     * Parsing the name and arguments of a rule
     *
     * @param string $rule
     *
     * @return void
     */
    private function parseRule(&$rule)
    {
        $result = [
            'name' => $rule,
            'arguments' => []
        ];

        // If validation rule has brackets then get the rule name and arguments
        if (strpos($rule, '(') && preg_match('#^([a-zA-Z0-9_]+)\((.+?)\)$#', $rule, $matches)) {
            $result['name'] = $matches[1];
            $result['arguments'] = explode(',', $matches[2]);
        }

        if ( ! in_array($result['name'], self::$supportedRules)) {
            throw new InvalidArgumentException(sprintf('Rule does not exist: "%s"', $result['name']));
        }

        // Prefix to match internal method name
        $result['method'] = 'check' . $result['name'];

        $rule = $result;
    }

    /**
     * Check value exists
     *
     * @param mixed $value
     *
     * @return boolean
     */
    private function checkBlank($value)
    {
        $value = trim($value);

        return (empty( $value ) && ! is_numeric( $value ));
    }

    /**
     * Validate value is valid email address
     *
     * @param mixed $value
     *
     * @return boolean
     */
    private function checkEmail($value)
    {
        return filter_var($value, FILTER_VALIDATE_EMAIL);
    }

    /**
     * Validate value maximum length
     *
     * @param mixed   $value
     * @param integer $length
     *
     * @return boolean
     */
    private function checkMaxLength($value, $length)
    {
        return (mb_strlen($value) <= $length);
    }

    /**
     * Validate value minimum length
     *
     * @param mixed   $value
     * @param integer $length
     *
     * @return boolean
     */
    private function checkMinLength($value, $length)
    {
        return (mb_strlen($value) >= $length);
    }

    /**
     * Validate value exceeds minimum
     *
     * @param mixed   $value
     * @param integer $min
     *
     * @return boolean
     */
    private function checkMin($value, $min)
    {
        return ($value >= $min);
    }

    /**
     * Validate value is between
     *
     * @param mixed   $value
     * @param integer $min
     * @param integer $max
     *
     * @return boolean
     */
    private function checkBetween($value, $min, $max)
    {
        return ($value >= $min && $value <= $max);
    }

    /**
     * Validate value does not exceed maximum
     *
     * @param mixed   $value
     * @param integer $max
     *
     * @return boolean
     */
    private function checkMax($value, $max)
    {
        return ($value <= $max);
    }

    /**
     * Validate value is numeric
     *
     * @param mixed $value
     *
     * @return boolean
     */
    private function checkNumeric($value)
    {
        return is_numeric($value);
    }

    /**
     * Validate value is integer
     *
     * @param mixed $value
     *
     * @return boolean
     */
    private function checkInteger($value)
    {
        return (is_int($value) || ($value == (string) (int) $value));
    }

    /**
     * Validate value is float
     *
     * @param mixed $value
     *
     * @return boolean
     */
    private function checkFloat($value)
    {
        return (is_float($value) || ($value == (string) (float) $value));
    }

    /**
     * Validate value is alpha/text
     *
     * @param mixed $value
     *
     * @return boolean
     */
    private function checkAlpha($value)
    {
        return (preg_match('#^[a-zA-ZÀ-ÿ]+$#', $value) == 1);
    }

    /**
     * Validate value is alpha numeric
     *
     * @param mixed $value
     *
     * @return boolean
     */
    private function checkAlphaNum($value)
    {
        return (preg_match('#^[a-zA-ZÀ-ÿ0-9]+$#', $value) == 1);
    }

    /**
     * Validate value equals variable
     *
     * @param mixed $value
     * @param mixed $match
     *
     * @return boolean
     */
    private function checkEquals($value, $match)
    {
        return ($value === $match);
    }

    /**
     * Validate value length
     *
     * @param mixed   $value
     * @param integer $length
     *
     * @return boolean
     */
    private function checkLength($value, $length)
    {
        return (mb_strlen($value) === $length);
    }

}