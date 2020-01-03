<?php
return [
    'language'   => 'ru-RU',
    'aliases'    => [
        '@bower' => '@vendor/bower-asset',
        '@npm'   => '@vendor/npm-asset',
    ],
    'vendorPath' => dirname(dirname(__DIR__)) . '/vendor',
    'components' => [
        'cache' => [
            'class' => 'yii\caching\FileCache',
        ],

        'authManager' => [
            'class'          => 'yii\rbac\PhpManager',
            'itemFile'       => '@rbac/items.php',
            'assignmentFile' => '@rbac/assignments.php',
            'ruleFile'       => '@rbac/rules.php',
            'defaultRoles'   => ['admin'],
        ],
    ],
];
