<?php
/*
 * This file is part of the Form package.
 *
 * (c) Unit6 <team@unit6websites.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

require realpath(dirname(__FILE__) . '/../autoload.php');
require realpath(dirname(__FILE__) . '/../vendor/autoload.php');

use Unit6\Form;

$template = new Form\Template();

$format = '<div class="form-group"><label for="{id}">{label}</label>'
. '<input type="{type}" name="{name}" value="{value}" id="{id}" class="form-control" {attributes} />{datalist}</div>';
$template->setInput($format);

$format = '<div class="checkbox"><label><input type="{type}"> {label}</label></div>';
$template->setInput($format, 'checkbox');

$format = '<input type="{type}" name="{name}" value="{value}" id="{id}" class="form-control" {attributes} />';
$template->setInput($format, 'hidden');

$format = '<div class="form-group"><label for="{id}">{label}</label>'
. '<textarea name="{name}" class="form-control" id="{id}" {attributes}>{value}</textarea></div>';
$template->setTextarea($format);

$format = '<button type="{type}" class="btn" {attributes}>{label}</button>';
$template->setButton($format);

$format = '<div class="form-group"><label for="{id}">{label}</label><select class="form-control" id="{id}" name="{name}" {attributes}>{options}</select></div>';
$template->setSelect($format);

$form = (new Form\Builder('login-form', 'post', 'processor.php', $template, [
        'class' => 'form',
        'data' => [
            'foo' => 'bar'
        ]
    ]))
    ->withInput('email', 'email', 'Email', 'j.smith@example.com', [
        'attributes' => [
            'class' => 'required',
            'data' => [
                'format' => 'email',
                'hint' => 'Please use a valid email address'
            ],
            'aria' => [
                'required' => 'true',
                'visible' => 'true'
            ]
        ],
        'rules' => [
            'Required',
            'MinLength(5)',
            'MaxLength(25)',
            'Email',
            'IsDisposable' => function ($value) {
                return ! (strpos($value, '@example.org') !== false);
            }
        ]
    ])
    ->withInput('text', 'name', 'Name', 'John Smith')
    ->withInput('password', 'password', 'Password', null, [
        'rules' => [
            //'Required',
            'Integer',
            'Numeric',
            'Min(5)',
            'Max(25)',
            'Between(5,25)',
            'MinLength(1)',
            'MaxLength(2)',
        ]
    ])
    ->withInput('checkbox', 'remember_me', 'Remember Me')
    ->withTextarea('message', 'Message', 'This is a message.')
    ->withSelect('status', 'Status', 'active',[
        [
            'value' => '',
            'label' => 'Please select...',
        ],
        [
            'value' => 'active',
            'label' => 'Active',
        ],
        [
            'value' => 'pending',
            'label' => 'Pending',
        ],
        [
            'label' => 'Archived',
        ],
        [
            'label' => 'Alternative',
            'disabled' => true,
            'group' => [
                [
                    'value' => 'on',
                    'label' => 'On',
                ],
                [
                    'label' => 'Off',
                ]
            ]
        ]
    ], [
        'attributes' => [
            'class' => 'required'
        ]
    ])
    ->withInput('text', 'location', 'Location', null, [
        'attributes' => [
            'class' => 'required'
        ],
        'list' => [
            'London',
            'New York',
            'Paris'
        ]
    ])
    ->withButton('submit', 'submit', 'Save', null, [
        'attributes' => [
            'class' => 'btn-success'
        ]
    ]);

//$form();

//var_dump($_SESSION); exit;

//var_dump($form); exit;