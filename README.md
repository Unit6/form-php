# unit6/form

Simple form builder library.

### Example

```php

use Unit6\Form;

$format = '<div class="form-group"><label for="{id}">{label}</label>'
. '<input type="{type}" name="{name}" value=":value" id="{id}" class="form-control {class}" {attr} /></div>';


$template = new Form\Template();
$template->setInput($format);

$form = (new Form\Builder($template))
    ->withInput('email', 'email', 'Email', 'j.smith@example.com', [
        'data' => [
            'enabled' => 'Y',
            'worldpay' => 'number'
        ]
        'aria-visible' => 'true',
        'class' => 'required'
    ])
    ->withInput('text', 'name', 'Name', 'John Smith')
    ->withInput('password', 'Password')
    ->withInput('checkbox', 'remember_me', 'Remember Me')
    ->withTextarea('')
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
        'class' => 'required'
    ]);

$form();

$elements = $form->getElements();

$form->open();
$elements->render('#email');
$elements->render('#password');
$elements->render('#payment-button');
$elements->render('#cancel-button');
$form->close();

```

### Requirements

Following required dependencies:

- PHP 5.6.x

### License

This project is licensed under the MIT license -- see the `LICENSE.txt` for the full license details.

### Acknowledgements

Some inspiration has been taken from the following projects:

- [adamwathan/form](https://github.com/adamwathan/form)
- [auraphp/Aura.Html](https://github.com/auraphp/Aura.Html)
- [butterfly-project/form](https://github.com/butterfly-project/form)
- [ezyang/htmlpurifier](https://github.com/ezyang/htmlpurifier)
- [LaravelCollective/html](https://github.com/LaravelCollective/html)
- [laravie/html](https://github.com/laravie/html)
- [LRotherfield/Nibble-Forms](https://github.com/LRotherfield/Nibble-Forms)
- [Masterminds/html5-php](https://github.com/Masterminds/html5-php)
- [neos/form](https://github.com/neos/form)
- [oscarotero/form-manager](https://github.com/oscarotero/form-manager)
- [StydeNet/html](https://github.com/StydeNet/html)
- [symfony/form](https://github.com/symfony/form)
- [ucsdmath/Html](https://github.com/ucsdmath/Html)
- [zendframework/zend-form](https://github.com/zendframework/zend-form)