<?php
/**
 * Created by PhpStorm.
 * User: Blackcode
 * Date: 09.04.2017
 * Time: 16:59
 */

namespace air\components\controllers;


use yii\web\Controller;

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
        //$backendTheme = $this->module->backendTheme;



        //exit;

    }

}