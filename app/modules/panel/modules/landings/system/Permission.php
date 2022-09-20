<?php
$permission = array(
    '*' => array(
        '*' => array(
            'allow' => false,
            'redirect' => url('panel','login')
        ),
        'moder' => array(
            'allow' => true
        ),
        'office_admin' => array(
            'allow' => true
        ),
        'multi_office_admin' => array(
            'allow' => true
        ),
        'admin' => array(
            'allow' => true
        )
    ),
);




/* End of file */