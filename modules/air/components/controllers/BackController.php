<?php
/**
 * Created by PhpStorm.
 * User: Blackcode
 * Date: 09.04.2017
 * Time: 16:59
 */

namespace air\components\controllers;


use yii\web\Controller;
use yii\web\User;

class BackController extends Controller
{
    public function accessRules()
    {
        return [
            ['allow', 'roles' => ['admin']],
            ['deny']
        ];
    }

    public function init(){
        parent::init();

        $this->layout = $this->module->getBackendLayoutAlias();
        //debug_(\Yii::$app->getModule('user'));//->loginUrl = 'sex';
       // \Yii::$app->user->loginUrl = ['zaluppa\dc'];

        //$backendTheme = $this->module->backendTheme;



        //exit;

    }

}