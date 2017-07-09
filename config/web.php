<?php

$params = require(__DIR__ . '/params.php');
Yii::setAlias('@air', __DIR__ . '/../modules/air/');
Yii::setAlias('@application', __DIR__ . '/../');

$config = [
    'id' => 'basic',
    'basePath' => dirname(__DIR__),
    'bootstrap' => ['log'],
    'components' => [
        'request' => [
            // !!! insert a secret key in the following (if it is empty) - this is required by cookie validation
            'cookieValidationKey' => 'Zb4cQ40HpJWdS2QkZZUeVYgaGWQf9YmF',
            'baseUrl' => '',
        ],
        'moduleManager' => [
          'class' => 'air\components\ModuleManager',
        ],
        'cache' => [
            'class' => 'yii\caching\FileCache',
        ],
        /*'user' => [
            'identityClass' => 'app\models\User',
            'enableAutoLogin' => true,
        ],*/
        'errorHandler' => [
            'errorAction' => 'site/error',
        ],
        'mailer' => [
            'class' => 'yii\swiftmailer\Mailer',
            // send all mails to a file by default. You have to set
            // 'useFileTransport' to false and configure a transport
            // for the mailer to send real emails.
            'useFileTransport' => true,
        ],
        'log' => [
            'traceLevel' => YII_DEBUG ? 3 : 0,
            'targets' => [
                [
                    'class' => 'yii\log\FileTarget',
                    'levels' => ['error', 'warning'],
                ],
            ],
        ],
        'db' => require(__DIR__ . '/db.php'),

        'urlManager' => [
            'enablePrettyUrl' => true,
            'showScriptName' => false,
            'rules' => [
                '/backend' => '/air/backend/index',
              // '/backend/login' => '/user/account/backendlogin',
              '/backend/<action:(AjaxFileUpload|AjaxImageUpload|AjaxImageUploadCKE|index|settings|flushDumpSettings|modulesettings|savemodulesettings|themesettings|modupdate|help|ajaxflush|transliterate)>' => '/air/backend/<action>',
              // '/backend/<module:\w+>/<controller:\w+>' => '/<module>/<controller>Backend/index',
              // '/backend/<module:\w+>/<controller:\w+>/<action:\w+>/<id:\d+>' => '/<module>/<controller>Backend/<action>',
              // '/backend/<module:\w+>/<controller:\w+>/<action:\w+>' => '/<module>/<controller>Backend/<action>',
            ],
        ],

    ],
    'params' => $params,
    'modules' =>[
        'air' => [
            'class' => 'app\modules\air\AirModule'
        ]
    ]

];

if (YII_ENV_DEV) {
    // configuration adjustments for 'dev' environment
    $config['bootstrap'][] = 'debug';
    $config['modules']['debug'] = [
        'class' => 'yii\debug\Module',
        // uncomment the following to add your IP if you are not connecting from localhost.
        //'allowedIPs' => ['127.0.0.1', '::1'],
    ];

    $config['bootstrap'][] = 'gii';
    $config['modules']['gii'] = [
        'class' => 'yii\gii\Module',
        // uncomment the following to add your IP if you are not connecting from localhost.
        //'allowedIPs' => ['127.0.0.1', '::1'],
    ];
}

return $config;
