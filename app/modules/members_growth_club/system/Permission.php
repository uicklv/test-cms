<?php
$scope = 'club'; // Scope

$permission = array(
    '*' => array(
        '*' => array(
            'allow' => true,
        ),
        'guest' => array(
            'allow' => false,
            'redirect' => url('members-growth-club/login')
        )
    ),

    'login' => array(
        '*' => array(
            'allow' => false,
            'redirect' => url('members-growth-club')
        ),
        'guest' => array(
            'allow' => true,
        )
    ),

    'register' => array(
        '*' => array(
            'allow' => false,
            'redirect' => url('members-growth-club')
        ),
        'guest' => array(
            'allow' => true,
        )
    ),

    'logout' => array(
        '*' => array(
            'allow' => true
        )
    ),
);

/* End of file */