<?php

use yii\bootstrap\NavBar;
//debug_($modules);
NavBar::begin([
    'brandLabel' => \yii\helpers\Html::img( $mainAsset[1] . '/img/logo.png',[] ),
    'brandUrl' => \yii\helpers\Url::to(["/air/backend/index"]),
    'options'  => [
        'class' => ['navbar','navbar-defaultd', 'navbar-fixed-top'],
    ],
    'innerContainerOptions' => [
        'class' => ['container-fluid'],
    ]
    
]);

echo \yii\bootstrap\Nav::widget([
    'items' => $modules,
    'options' => ['class' =>'nav-pills'], // set this to nav-tab to get tab-styled navigation
]);




NavBar::end();


