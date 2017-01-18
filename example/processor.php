<?php
/*
 * This file is part of the Form package.
 *
 * (c) Unit6 <team@unit6websites.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

require_once 'Form.php';

use Unit6\Form;

// debug
#$_POST['email'] = '      ';
#$_POST['csrf'] = '5000';

try
{
    $form->validate( $_POST );
}
catch (Form\RequestForgeryException $e)
{
    var_dump($e->getMessage());
    #var_dump($e->getCode());
}
catch (Form\ValidationException $e)
{
    var_dump($e->getMessage());
    #var_dump($e->getField());
    #var_dump($e->getField()->getValue());
    #var_dump($e->getCode());
};

var_dump($_POST);