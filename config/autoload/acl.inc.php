<?php

$acl_admin = include 'acl.admin.inc.php';
$acl_guest = include 'acl.guest.inc.php';

return [
    'aclroles' => [ 
        'guest' => [
            'parent' => [] // inheriting from parent
        ],
        'administrator' => [
            'parent' => ['guest']
        ]
    ],

    'aclrules' => [
        'guest' => $acl_guest,
        'administrator' => $acl_admin,
    ]
];