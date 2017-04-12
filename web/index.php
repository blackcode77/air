<?php

// comment out the following two lines when deployed to production
defined('YII_DEBUG') or define('YII_DEBUG', true);
defined('YII_ENV') or define('YII_ENV', 'dev');

require(__DIR__ . '/../vendor/autoload.php');
require(__DIR__ . '/../vendor/yiisoft/yii2/Yii.php');
require_once __DIR__ . '/../functions.php';



$config = require(__DIR__ . '/../config/web.php');

$confManager = new \air\components\ConfigManager();
$confManager->sentEnv(\air\components\ConfigManager::ENV_WEB);

//debug_(Yii::getAlias('@air'));
//debug_($confManager->merge($config));

//ebug( Yii::$aliases );

(new yii\web\Application($confManager->merge($config)))->run();
