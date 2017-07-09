<?php
/**
 * Created by PhpStorm.
 * User: Blackcode
 * Date: 17.04.2017
 * Time: 11:06
 */

namespace air\widgets;


use yii\base\Widget;
use yii\bootstrap\Alert;

class AlertWidget extends Widget
{
    public function run(){
//debug_(\Yii::$app->session->getFlash('ok'));
        if ( null !== \Yii::$app->session->getFlash('ok') ){
            return Alert::widget([
                'options' => [
                    'class' => 'alert-info',
                ],
                'body' => \Yii::$app->session->getFlash('ok'),
            ]);
        }

        if ( null !== \Yii::$app->session->getFlash('danger') ){
            return Alert::widget([
                'options' => [
                    'class' => 'alert-warning',
                ],
                'body' => \Yii::$app->session->getFlash('danger'),
            ]);
        }
    }
}