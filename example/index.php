<?php

    require_once 'Form.php';

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Unit6\Form</title>

    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css" integrity="sha384-1q8mTJOASx8j1Au+a5WDVnPi2lkFfwwEAa8hDDdjZlpLegxhjVME1fgjWPGmkzs7" crossorigin="anonymous">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap-theme.min.css" integrity="sha384-fLW2N01lMqjakBkx3l/M9EahuwpSfeNvV63J5ezn3uZzapT0u7EYsXMjQV+0En5r" crossorigin="anonymous">

    <!--[if lt IE 9]>
    <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js" integrity="sha384-FFgGfda92tXC8nCNOxrCQ3R8x1TNkMFqDZVQdDaaJiiVbjkPBXIJBx0o7ETjy8Bh" crossorigin="anonymous"></script>
    <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js" integrity="sha384-ZoaMbDF+4LeFxg6WdScQ9nnR1QC2MIRxA1O9KWEXQwns1G8UNyIEZIQidzb0T1fo" crossorigin="anonymous"></script>
    <![endif]-->
</head>
<body>

    <div class="container">

        <div class="row">

             <div class="col-md-12">

                <h1>Unit6\Form</h1>
                <p class="lead">Basic example of a Bootstrap form</p>

                <?php

                    #$form();

                    $form->open();

                    #$elements = $form->getElements();
                    #var_dump($elements);

                    $form->element('csrf');
                    $form->element('name');
                    $form->element('email');
                    $form->element('password');
                    $form->element('message');
                    $form->element('remember_me');
                    $form->element('status');
                    $form->element('location');
                    $form->element('submit');

                    $form->close();

                    #var_dump($_SESSION); exit;

                ?>

            </div>

        </div>

    </div><!-- /.container -->

    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js" integrity="sha384-6ePHh72Rl3hKio4HiJ841psfsRJveeS+aLoaEf3BWfS+gTF0XdAqku2ka8VddikM" crossorigin="anonymous"></script>

    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/js/bootstrap.min.js" integrity="sha384-0mSbJDEHialfmuBBQP6A4Qrprq5OVfW37PRR3j5ELqxss1yVqOtnepnHVP9aJ7xS" crossorigin="anonymous"></script>

</body>
</html>