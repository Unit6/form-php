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

use BadMethodCallException;
use InvalidArgumentException;

/**
 * Form Template
 *
 *
 */
class Template
{
    /**
     * Formats for Input
     *
     * @var array
     */
    protected $input;

    /**
     * Format for Textarea
     *
     * @var string
     */
    protected $textarea;

    /**
     * Format for Button
     *
     * @var string
     */
    protected $button;

    /**
     * Format for Select
     *
     * @var string
     */
    protected $select;

    /**
     * Set Input Format
     *
     * Assign a format for an input element.
     *
     * @param string $format Format for an input element
     *
     * @return void
     */
    public function setInput($format, $type = 'default')
    {
        if ($type !== 'default' && ! in_array($type, Element\Input::$typeOptions)) {
            throw new InvalidArgumentException(sprintf('Unsupported input type "%s" provided', $type));
        }

        $this->input[$type] = $format;
    }

    /**
     * Set Textarea Format
     *
     * Assign a format for a textarea element.
     *
     * @param string $format Format for a textarea element
     *
     * @return void
     */
    public function setTextarea($format)
    {
        $this->textarea = $format;
    }

    /**
     * Set Button Format
     *
     * Assign a format for a button element.
     *
     * @param string $format Format for a button element
     *
     * @return void
     */
    public function setButton($format)
    {
        $this->button = $format;
    }

    /**
     * Set Select Format
     *
     * Assign a format for a select element.
     *
     * @param string $format Format for a select element
     *
     * @return void
     */
    public function setSelect($format)
    {
        $this->select = $format;
    }

    /**
     * Get Input Format
     *
     * @return string
     */
    public function getInput($type)
    {
        return (isset($this->input[$type]) ? $this->input[$type] : $this->input['default']);
    }

    /**
     * Get Textarea Format
     *
     * @return string
     */
    public function getTextarea()
    {
        return $this->textarea;
    }

    /**
     * Get Textarea Format
     *
     * @return string
     */
    public function getButton()
    {
        return $this->button;
    }

    /**
     * Get Select Format
     *
     * @return string
     */
    public function getSelect()
    {
        return $this->select;
    }

    /**
     * Template for Field
     *
     * Determine whether or not a template exists for a particular field.
     *
     * @param string $tag Field tag name.
     *
     * @return string
     */
    public function forField($tag, $type = FALSE)
    {
        $method = 'get' . ucwords($tag);

        if ( ! method_exists($this, $method)) {
            throw new BadMethodCallException(sprintf('Undefined Template field method: "%s"', $method));
        }

        return call_user_func([$this, $method], $type);
    }

    /**
     * Slugify Text
     *
     * @see http://stackoverflow.com/a/2955878/
     *
     * @param string $str
     *
     * @return string
     */
    public static function slug($text)
    {
        // replace non letter or digits by -
        $text = preg_replace('~[^\pL\d]+~u', '-', $text);

        // transliterate
        $text = iconv('utf-8', 'us-ascii//TRANSLIT', $text);

        // remove unwanted characters
        $text = preg_replace('~[^-\w]+~', '', $text);

        // trim
        $text = trim($text, '-');

        // remove duplicate -
        $text = preg_replace('~-+~', '-', $text);

        // lowercase
        $text = strtolower($text);

        if (empty($text)) return 'n-a';

        return $text;
    }

    /**
     * Merge Data to Format
     *
     * Returns formatted string containing data.
     *
     * @param string $format HTML format.
     * @param array  $data   Associative array containing data to be merged.
     *
     * @return string
     */
    public static function merge($format, $data)
    {
        return str_replace(array_keys($data), array_values($data), $format);
    }

    /**
     * Parse Format Attributes
     *
     * Merge the format attributes of the field tag
     * with the fields designated attributes.
     *
     * @see http://stackoverflow.com/a/28086413/
     *
     * @param string $tag        Name of tag to parse.
     * @param string $format     Field formatting.
     * @param array  $attributes Line of attributes.
     *
     * @return void
     */
    public static function formatAttributes($tag, &$format, array &$attributes)
    {
        $matches = [];
        $pattern = '/(?:<' . $tag . '|(?<!^)\G)\h*(\w+)="([^"]+)"(?=.*?>)/';

        $found = preg_match_all($pattern, $format, $matches);

        $attr = [];

        if ($found) {
            list($captures, $keys, $values) = $matches;

            for ($i = 0; $i < $found; $i++) {
                $attr[$keys[$i]] = [
                    'capture' => $captures[$i],
                    'value' => $values[$i]
                ];
            }

            // Attempt to merge class attributes to the format location.
            // removing them from the list of designated attributes.
            if (isset($attr['class'], $attributes['class'])) {
                $capture = $attr['class']['capture'];
                $value = $attr['class']['value'];
                $replace = str_replace($value, (empty($value) ? '' : $value . ' ') . $attributes['class'], $capture);
                $format = str_replace($capture, $replace, $format);
                unset($attributes['class']);
            }
        }
    }
}