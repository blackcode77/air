<?php
/**
 * Created by PhpStorm.
 * User: Blackcode
 * Date: 11.04.2017
 * Time: 0:34
 */

namespace air\widgets;


use yii\base\Widget;

class AdminPanelWidget extends Widget
{
    public $view = 'default';
    public $mainAsset;
    public function init()
    {
        parent::init();
        
    }

    public function run()
    {
        return $this->render($this->view, [
                'modules' => \Yii::$app->moduleManager->getModules(true),
                'mainAsset' => $this->mainAsset,
            ]
        );
        //"Hello from widget!";
        //parent::run();
    }
}