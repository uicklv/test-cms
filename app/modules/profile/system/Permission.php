<?php
$scope = 'candidate'; // Scope

$permission = array(
    '*' => array(
        '*' => array(
            'allow' => false,
            'redirect' => url('login')
        ),
        'user' => array(
            'allow' => true
        )
    ),

    'profile' => array(
        '*' => array(
            'allow' => false,
            'redirect' => url('login')
        ),
        'user' => array(
            'allow' => true
        )
    ),

    'login' => array(
        '*' => array(
            'allow' => true
        ),
        'user' => array(
            'allow' => false,
            'redirect' => url('profile')
        )
    ),

    'register' => array(
        '*' => array(
            'allow' => true
        ),
        'user' => array(
            'allow' => false,
            'redirect' => url('profile')
        )
    ),

    'email_confirmation' => array(
        '*' => array(
            'allow' => true
        ),
        'user' => array(
            'allow' => false,
            'redirect' => url('profile')
        )
    ),

    'restore_password' => array(
        '*' => array(
            'allow' => true
        ),
        'customer' => array(
            'allow' => false,
            'redirect' => url('portal')
        ),
    ),

    'restore_process' => array(
        '*' => array(
            'allow' => true
        ),
        'customer' => array(
            'allow' => false,
            'redirect' => url('portal')
        ),
    ),

    'salary_survey' => array(
        '*' => array(
            'allow' => true
        )
    ),

    'get_role' => array(
        '*' => array(
            'allow' => true
        )
    ),

    'logout' => array(
        '*' => array(
            'allow' => true
        )
    )
);

/* End of file */