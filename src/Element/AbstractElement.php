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

/**
 * Abstract for Form Element
 *
 * Shared properties and methods for a standard form element.
 */
abstract class AbstractElement
{
    /**
     * Element Identifier
     *
     * @var string
     */
    protected $id;

    /**
     * HTML Tag Name
     *
     * @var string
     */
    protected $tag;

    /**
     * Element Attributes
     *
     * @var array
     */
    protected $attributes;

    /**
     * Set Element Attributes
     *
     * Parse multi-dimensional associative array to create
     * a one dimensional associative array.
     *
     * @param array|null $attributes The remaining attributes for the element
     *
     * @return void
     */
    public function setAttributes(array $attributes = null)
    {
        $this->attributes = self::parseAttributes($attributes);
    }

    /**
     * Parse Element Attributes
     *
     * Parse multi-dimensional associative array to create
     * a one dimensional associative array.
     *
     * @param array|null $attributes The remaining attributes for the element
     *
     * @return void
     */
    public static function parseAttributes(array $attributes = null)
    {
        $list = null;

        if ( ! empty($attributes)) {
            $list = [];
            foreach ($attributes as $key => $value) {
                if (is_array($value)) {
                    foreach ($value as $k => $v) {
                        $list[$key . '-' . $k] = $v;
                    }
                } else {
                    $list[$key] = $value;
                }
            }
        }

        return $list;
    }

    /**
     * Get Element Attributes
     *
     * @return string
     */
    public function getAttributes()
    {
        return $this->attributes;
    }

    /**
     * Get Element Rules
     *
     * @return array
     */
    public function getRules()
    {
        return $this->rules;
    }

    /**
     * Get Element Attributes Line
     *
     * Concat element attributes into string
     *
     * @return string $attributes String of remaining attributes
     */
    public function getAttributesLine()
    {
        return self::parseAttributesLine($this->getAttributes());
    }

    /**
     * Parse Element Attributes Line
     *
     * Concat element attributes into string
     *
     * @return string
     */
    public static function parseAttributesLine(array $attributes = null)
    {
        $pieces = [];

        if ( ! empty($attributes)) {
            foreach ($attributes as $key => $value) {
                $pieces[] = sprintf('%s="%s"', self::sanitize($key), self::sanitize($value));
            }
        }

        return implode(' ', $pieces);
    }

    /**
     * Set Element Identifier
     *
     * @param string $id ID to assign.
     *
     * @return void
     */
    public function setId($id)
    {
        $this->id = $id;
    }

    /**
     * Get Element Identifier
     *
     * @return string
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Get Element Tag
     *
     * @return string
     */
    public function getTag()
    {
        return $this->tag;
    }

    /**
     * Get Element Type
     *
     * @return string
     */
    public function getType()
    {
        return (isset($this->type) ? $this->type : null);
    }

    /**
     * Sanitize Input
     *
     * @see http://stackoverflow.com/a/1996141/
     *
     * @param string $str
     *
     * @return string
     */
    public static function sanitize($str)
    {
        return htmlspecialchars($str, ENT_QUOTES, 'UTF-8');
    }
}
