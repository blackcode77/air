<?php
/**
 * Created by PhpStorm.
 * User: Blackcode
 * Date: 09.04.2017
 * Time: 17:01
 */

namespace app\modules\air\controllers;
//namespace air\controllers;

//debug_( \Yii::getAlias('@air') );
use air\components\controllers\BackController;
use air\components\WebModule;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\web\Controller;
use yii\web\HttpException;

class BackendController extends BackController
{
    public function actionIndex(){

        return $this->render('index');
    }

    /**
     * Формирует поле для редактирование параметра модуля
     * @param \yupe\components\WebModule $module
     * @param $param
     * @return string
     */
    private function getModuleParamRow(WebModule $module, $param)
    {
        //debug_($module);
        $editableParams = $module->getEditableParams();
        $moduleParamsLabels = ArrayHelper::merge($module->getParamsLabels(), $module->getDefaultParamsLabels());

        $res = Html::label($moduleParamsLabels[$param], $param);
        /* если есть ключ в массиве параметров, то значит этот параметр выпадающий список в вариантами */
        if (array_key_exists($param, $editableParams)) {


            $res.= Html::dropDownList(
                $param,
                $module->{$param},
                $editableParams[$param],
                ['class' => 'form-control', 'empty' => '--choose--']
            );
        } else {
            //debug_($param);
            $res.= Html::textInput($param, $module->{$param}, ['class' => 'form-control']);
        }
        //debug_($res);
        return $res;
    }

    public function actionModulesettings($module){
        if (!($module = \Yii::$app->getModule($module))) {
            throw new HttpException ('Настройки для этого модуля не предусмотрены!');//CHttpException(404, Yii::t('YupeModule.yupe', 'Setting page for this module is not available!'));
        }

        $editableParams = $module->getEditableParams();
        $paramGroups = $module->getEditableParamsGroups();

        $groups = [];

        foreach ($paramGroups as $name => $group) {
            $title = isset($group['label']) ? $group['label'] : $name;
            $groups[$title] = [];
            if (isset($group['items'])) {
                foreach ((array)$group['items'] as $item) {
                    /*удаляем элементы, которые были в группах*/
                    if (($key = array_search($item, $editableParams)) !== false) {
                        unset($editableParams[$key]);
                    } else {
                        unset($editableParams['item']);
                    }
                    unset($editableParams[$item]);
                    $groups[$title][] = $this->getModuleParamRow($module, $item);
                }
            }
        }

        /* если остались параметры без групп, то засунем их в одну группу */
        if ($editableParams) {
            $title = 'Other';
            $groups[$title] = [];
            foreach ((array)$editableParams as $key => $params) {
                /* из-за формата настроек параметров название атрибута будет или ключом, или значением */
                $groups[$title][] = $this->getModuleParamRow($module, is_string($key) ? $key : $params);
            }
        }

        $this->render('modulesettings', ['module' => $module, 'groups' => $groups,]);

    }

}