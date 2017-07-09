<?php
return [
    'module' => [
        'class' => 'app\modules\user\UserModule',//  application.modules.user.UserModule',
        'documentRoot' => $_SERVER['DOCUMENT_ROOT'],
        'avatarsDir' => 'avatars',
        'notifyEmailFrom' => 'test@test.ru',
    ],

    'component' => [
        // компонент Yii::app()->user, подробнее http://www.yiiframework.ru/doc/guide/ru/topics.auth
        'user' => [
            //'class' => 'Sex',
            'identityClass' => 'app\modules\user\components\IdentityUser',
            'enableAutoLogin' => true,
            'loginUrl' => ['user/account/login'],
        ],
        /*'user' => [
            'class' => 'application.modules.user.components.YWebUser',
            'loginUrl' => ['/user/account/login'],
            'identityCookie' => [
                'httpOnly' => true,
            ],
        ],*/
        /*'userManager' => [
            'class' => 'application.modules.user.components.UserManager',
            'hasher' => [
                'class' => 'application.modules.user.components.Hasher',
            ],
            'tokenStorage' => [
                'class' => 'application.modules.user.components.TokenStorage',
            ],
        ],*/
        /*'authenticationManager' => [
            'class' => 'application.modules.user.components.AuthenticationManager',
        ],*/
        /*'eventManager' => [
            'class' => 'yupe\components\EventManager',
            'events' => [
                'user.success.registration' => [
                    ['UserManagerListener', 'onUserRegistration'],
                ],
                'user.success.registration.need.activation' => [
                    ['UserManagerListener', 'onUserRegistrationNeedActivation'],
                ],
                'user.success.password.recovery' => [
                    ['UserManagerListener', 'onPasswordRecovery'],
                ],
                'user.success.activate.password' => [
                    ['UserManagerListener', 'onSuccessActivatePassword'],
                ],
                'user.success.activate.account' => [
                    ['UserManagerListener', 'onSuccessActivateAccount']
                ],
                'user.success.email.confirm' => [
                    ['UserManagerListener', 'onSuccessEmailConfirm'],
                ],
                'user.success.email.change' => [
                    ['UserManagerListener', 'onSuccessEmailChange'],
                ]
            ],
        ],*/
    ],
    'rules' => [
        '/login' => 'user/account/login',
        '/logout' => 'user/account/logout',
        '/registration' => 'user/account/registration',
        '/recovery' => 'user/account/recovery',
        '/activate/<token>' => 'user/account/activate',
        '/confirm/<token>' => 'user/account/confirm',
        '/recovery/<token>' => 'user/account/restore',
        '/user/account/captcha/refresh/<v>' => 'user/account/captcha/refresh',
        '/user/account/captcha/<v>' => 'user/account/captcha/',
        '/users' => 'user/people/index',
        '/users/<username>/' => 'user/people/userInfo',
        '/profile' => 'user/profile/profile',
        '/profile/password' => 'user/profile/password',
        '/profile/email' => 'user/profile/email',
    ],
];
