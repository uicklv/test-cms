<?php
$scope = 'portal'; // Scope

$permission = array(
    '*' => array(
        '*' => array(
            'allow' => false,
            'redirect' => url('portal/login')
        ),
        'customer' => array(
            'allow' => true
        )
    ),

    'login' => array(
        '*' => array(
            'allow' => true
        ),
        'customer' => array(
            'allow' => false,
            'redirect' => url('portal')
        ),
    ),

    'logout' => array(
        '*' => array(
            'allow' => true
        )
    )
);

/* End of file */