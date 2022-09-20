<?php
$scope = 'ld'; // Scope

$permission = array(
    '*' => array(
        '*' => array(
            'allow' => false,
            'redirect' => url('learning-development','login')
        ),
        'moder' => array(
            'allow' => true
        ),
    ),

    'login' => array(
        '*' => array(
            'allow' => true
        ),
        'moder' => array(
            'allow' => false,
            'redirect' => url('learning-development')
        )
    ),

    'logout' => array(
        '*' => array(
            'allow' => true
        )
    ),
);

/* End of file */