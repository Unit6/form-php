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

use Unit6\Session;

use InvalidArgumentException;

/**
 * Form Builder
 *
 *
 */
class Builder extends Element\AbstractElement
{
    /**
     * Templates for HTML elements
     *
     * @var Template
     */
    protected $template;

    /**
     * Form Elements
     *
     * @var CollectionInterface
     */
    protected $elements;

    /**
     * Form Identifier
     *
     * @var string
     */
    protected $id;

    /**
     * Form Method
     *
     * @var string
     */
    protected $method;

    /**
     * Form Action
     *
     * @var string
     */
    protected $action;

    /**
     * Form Attributes
     *
     * @var array
     */
    protected $attributes;

    /**
     * Session Manager
     *
     * @var Session\Manager
     */
    protected $session;

    /**
     * HTML Tag Name
     *
     * @var string
     */
    protected $tag = 'form';

    /**
     * Supported Form Methods
     *
     * @var string
     */
    protected static $methodOptions = [
        'get',
        'post'
    ];

    /**
     * Is CSRF required?
     *
     * @var boolean
     */
    protected $isRequestForgeryProtection;

    /**
     * Create a new form.
     *
     * @param string        $method     Method attribute for form
     * @param string        $action     Action attribute for form
     * @param Template|null $template   An object containing templates for HTML elements
     * @param array|null    $attributes Array of any another attributes for form
     *
     * @return void
     */
    public function __construct($id, $method, $action, Template $template = null, array $attributes = null)
    {
        if ( ! in_array($method, static::$methodOptions)) {
            throw new InvalidArgumentException(sprintf('Unsupported form method "%s" provided', $method));
        }

        $this->id = Template::slug($id);
        $this->method = $method;
        $this->action = $action;
        $this->template = $template;

        $this->setAttributes($attributes);

        $this->elements = new Collection();
    }

    /**
     * Print Form HTML
     *
     * @return void
     */
    public function __invoke()
    {
        $this->render();
    }

    /**
     * Form Input
     *
     * Add an input element to the form.
     *
     * @param string      $type       The type attribute
     * @param string      $name       The name attribute
     * @param string      $label      The text for the label
     * @param string|null $value      The value attribute
     * @param array|null  $attributes Array of any another attributes
     *
     * @return self
     */
    public function withInput($type, $name, $label, $value = null, array $attributes = null)
    {
        $field = new Element\Input($type, $name, $label, $value, $attributes);

        return $this->push($field);
    }

    /**
     * Form Textarea
     *
     * Add a textarea element to the form.
     *
     * @param string      $name       The name attribute
     * @param string      $label      The text for the label
     * @param string|null $value      The content attribute
     * @param array|null  $attributes Array of any another attributes
     *
     * @return self
     */
    public function withTextarea($name, $label, $value = null, array $attributes = null)
    {
        $field = new Element\Textarea($name, $label, $value, $attributes);

        return $this->push($field);
    }

    /**
     * Form Select
     *
     * Add an select element to the form.
     *
     * @param string      $name       The name attribute
     * @param string      $label      The text for the label
     * @param array       $options    The select options
     * @param string|null $value      The value attribute
     * @param array|null  $attributes Array of any another attributes
     *
     * @return self
     */
    public function withSelect($name, $label, $value = null, array $options = null, array $attributes = null)
    {
        $field = new Element\Select($name, $label, $value, $options, $attributes);

        return $this->push($field);
    }

    /**
     * Form Button
     *
     * Add a button element to the form.
     *
     * @param string      $type       The type attribute
     * @param string      $name       The name attribute
     * @param string      $label      The text for the label
     * @param string|null $value      The content attribute
     * @param array|null  $attributes Array of any another attributes
     *
     * @return self
     */
    public function withButton($type, $name, $label, $value = null, array $attributes = null)
    {
        $field = new Element\Button($type, $name, $label, $value, $attributes);

        return $this->push($field);
    }

    /**
     * Form Session
     *
     * Define a session manager to use for request forgery protection
     *
     * @param Session\Manager $session Session manager
     *
     * @return self
     */
    public function withSession(Session\Manager $session)
    {
        $clone = clone $this;
        $clone->session = $session;

        $clone->setRequestForgeryProtection();

        return $clone;
    }

    /**
     * Get Session
     *
     * @return Session\Manager
     */
    public function getSession()
    {
        return $this->session;
    }

    /**
     * Get Token
     *
     * @return string
     */
    public function getToken()
    {
        return self::token();
    }

    /**
     * Get Form Template
     *
     * @return Template
     */
    public function getTemplate()
    {
        return $this->template;
    }

    /**
     * Get Form Identifier
     *
     * @return string
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Get Form Method
     *
     * @return string
     */
    public function getMethod()
    {
        return $this->method;
    }

    /**
     * Get Form Action
     *
     * @return string
     */
    public function getAction()
    {
        return $this->action;
    }

    /**
     * Get Form Elements
     *
     * @return array
     */
    public function getElements()
    {
        return $this->elements->all();
    }

    /**
     * Add Form Field
     *
     * @param FieldInterface $field Form field to append.
     *
     * @return self
     */
    public function push(Element\FieldInterface $field)
    {
        $format = $this->getTemplate()->forField($field->getTag(), $field->getType());
        $field->assignTo($this->getId(), $format);

        $clone = clone $this;
        $clone->elements->set($field->getName(), $field);

        return $clone;
    }

    /**
     * Print Opening Tag
     *
     * @return void
     */
    public function open()
    {
        $format = '<form id="{id}" method="{method}" action="{action}" {attributes}>';

        $data = [
            '{id}'          => $this->getId(),
            '{method}'      => $this->getMethod(),
            '{action}'      => $this->getAction(),
            '{attributes}'  => $this->getAttributesLine()
        ];

        echo Template::merge($format, $data);
    }

    /**
     * Print Closing Tag
     *
     * @return void
     */
    public function close()
    {
        echo '</form>';
    }

    /**
     * Render Form
     *
     * @return void
     */
    public function render()
    {
        $this->open();

        foreach ($this->getElements() as $element) {
            $element->render($this->template);
        }

        $this->close();
    }

    /**
     * Render Element
     *
     * @param string $name Render element
     *
     * @return void
     */
    public function element($name)
    {
        $element = $this->elements->get($name);

        $element->render($this->template);
    }

    /**
     * Validate form fields
     *
     * @param array $input User input to be validated.
     *
     * @return void
     */
    public function validate(array $input)
    {
        $this->requestForgeryProtection($input);

        foreach ($this->elements as $element) {
            $validation = $element->getValidation();

            if ( ! ($validation instanceof Validation)) {
                continue;
            }

            $name = $element->getName();

            $element->setValue(isset($input[$name]) ? $input[$name] : null);

            $validation();
        }
    }

    /**
     * Generate Token
     *
     * @return void
     */
    private function token()
    {
        $session = $this->getSession();

        $key = sprintf('%s.csrf', $this->getId());

        $token = $session->get($key);

        if ( ! $token) {
            // Generate a new unique token
            if (function_exists('openssl_random_pseudo_bytes')) {
                // Generate a random pseudo bytes token if openssl_random_pseudo_bytes is available
                // This is more secure than uniqid, because uniqid relies on microtime, which is predictable
                $token = base64_encode(openssl_random_pseudo_bytes(32));
            } else {
                // Otherwise, fall back to a hashed uniqid
                $token = sha1(uniqid(NULL, TRUE));
            }

            $session->set($key, $token);
        }

        return $token;
    }

    /**
     * Form Cross-Site Request Forgery (CSRF)
     *
     * Add a hidden input element for POST requests.
     *
     * @return void
     */
    public function setRequestForgeryProtection()
    {
        $this->isRequestForgeryProtection = ('post' === $this->getMethod());

        if ( ! $this->isRequestForgeryProtection) {
            return;
        }

        $value = $this->getToken();

        $field = new Element\Input('hidden', 'csrf', null, $value);
        $field->assignTo($this->getId());

        $this->elements->set($field->getName(), $field);
    }

    /**
     * Validate CSRF token
     *
     * @param array $input User input to be validated.
     *
     * @return void
     */
    private function requestForgeryProtection(array $input)
    {
        // Only check CSRF on POST requests
        if ( ! $this->isRequestForgeryProtection) {
            return;
        }

        if ( ! isset($input['csrf'])) {
            throw new RequestForgeryException('Token missing from input');
        }

        if (empty($input['csrf'])) {
            throw new RequestForgeryException('Token in input is empty');
        }

        $key = sprintf('%s.csrf', $this->getId());
        $token = $this->getSession()->get($key);

        if ( ! $token) {
            throw new RequestForgeryException('Token missing from session');
        }

        if ($token !== $input['csrf']) {
            throw new RequestForgeryException('Token invalid');
        }
    }
}
