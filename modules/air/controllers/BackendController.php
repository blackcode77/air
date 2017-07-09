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
use air\models\SettingsModel;
use yii\filters\AccessControl;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\web\Controller;
use yii\web\HttpException;

class BackendController extends BackController
{
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                //'only' => ['index'],
                'rules' => [
                    /*[
                        'allow' => true,
                        'actions' => ['index', 'signup'],
                        'roles' => ['?'],
                    ],*/
                    [
                        'allow' => true,
                        'actions' => ['index'],
                        'roles' => ['@'],
                    ],
                ],
            ],
        ];
    }

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

        return $this->render('modulesettings', ['module' => $module, 'groups' => $groups,]);

    }

    /**
     * Экшен сохранения настроек модуля:
     *
     * @throws CHttpException
     *
     * @return void
     **/
    public function actionSavemodulesettings()
    {
        //debug_('fv');
        //$this-Ю
       // $this->redirect('http://ya.ru');
        if ( \Yii::$app->request->getIsPost()) {

            if (!($moduleId = \Yii::$app->request->post('module_id')) ) {
                throw new HttpException(404,  'Page was not found!');
            }
            //debug_($moduleId);

            if (!($module = \Yii::$app->getModule($moduleId))) {
                throw new HttpException(404,  'Модуль не найден!');
            }

            if ($this->saveParamsSetting($moduleId, $module->getEditableParamsKey())) {

                \Yii::$app->session->setFlash('ok', 'Настройки для модуля сохранены!');

                $module->getSettings(true);

            } else {
                \Yii::$app->session->setFlash('danger', 'Ошибка при сохранении в модуле "{$module}"!');

            }
            /*debug_(
                Url::to($module->getSettingsUrl())
        );*/
                //$module->getSettingsUrl()
            //);
            //die;
            //$this->redirect('http://ya.ru');
            return $this->redirect(Url::to($module->getSettingsUrl()));
        }
        throw new HttpException(404,  'Page was not foundd!');
    }

    /**
     * Метода сохранения настроек модуля:
     *
     * @param string $moduleId - идетификтор метода
     * @param array $params - массив настроек
     *
     * @return bool
     **/
    public function saveParamsSetting($moduleId, $params)
    {
        /**
         * params = Array   -  это параметры которые можно добавлять в БД для текущего модуля
                            (
                                [0] => coreCacheTime
                                [1] => theme
                                [2] => siteName
                                [3] => siteDescription
                                [4] => siteKeyWords
                                [5] => uploadPath
                                [6] => email
                                [7] => allowedIp
                                [8] => logo
                                [9] => allowedExtensions
                                [10] => mimeTypes
                                [11] => maxSize
                            )
         */
        $paramValues = [];

        // Перебираем все параметры модуля
        foreach ($params as $param_name) {
            $param_value = \Yii::$app->request->post($param_name, null);
            // Если параметр есть в post-запросе добавляем его в массив
            if ($param_value !== null) {
                $paramValues[$param_name] = $param_value;
            }
        }
        
        //debug_( $paramValues );


        // Запускаем сохранение параметров
        return SettingsModel::saveModuleSettings($moduleId, $paramValues);
    }


}