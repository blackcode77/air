<?php
/**
 * Created by PhpStorm.
 * User: Blackcode
 * Date: 19.04.2017
 * Time: 17:02
 */

namespace app\modules\user\controllers;


use air\components\controllers\FrontController;

class AccountController extends FrontController
{
    public function actions(){
        return [
            'login' => 'app\modules\user\controllers\account\LoginAction',
        ];
    }
}