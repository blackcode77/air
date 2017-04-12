<?php
/**
 * Created by PhpStorm.
 * User: Blackcode
 * Date: 10.04.2017
 * Time: 10:52
 */

namespace air\views\assets;

class AirAsset extends \yii\web\AssetBundle
{
    public $sourcePath = '@air/views/assets';
    //public $basePath = '@webroot';
    //public $baseUrl = '@web';
    public $css = [
        'css/styles.css',
        'css/bootstrap-notify.css',
        'css/flags.css'

    ];
    public $js = [
        'js/main.js',
        'js/bootstrap-notify.js',
        'js/jquery.li-translit.js'
    ];
    public $depends = [
        'yii\web\YiiAsset',
        'yii\bootstrap\BootstrapAsset',
    ];
}