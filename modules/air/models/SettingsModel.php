<?php
/**
 * Created by PhpStorm.
 * User: Blackcode
 * Date: 13.04.2017
 * Time: 14:51
 */

namespace air\models;


use yii\db\ActiveRecord;

class SettingsModel extends ActiveRecord
{
    public function Rules(){
        return [
            [['module_id', 'param_name'], 'required'],
            [['module_id', 'param_name'], 'string', 'max' => 100],
            ['param_value', 'string', 'max' => 500],
            //['user_id', 'type', 'integer'],
        ];
    }
    public static function tableName(){
        return '{{%yupe_settings}}';
    }

    /**
     * @param $moduleId
     * @param $paramValues
     * @return bool
     */
    public static function saveModuleSettings($moduleId, $paramValues)
    {
        //debug_($paramValues);
        foreach ($paramValues as $name => $value) {
            // Получаем настройку

            /*$setting = Settings::model()->find(
                'module_id = :module_id and param_name = :param_name',
                [':module_id' => $moduleId, ':param_name' => $name]
            );*/

            $setting = SettingsModel::find()
                                      ->where(['module_id'=>'yupe', 'param_name' =>$name])->one();

            //debug($setting->type);
            // Если новая запись
            if ($setting === null) {
                //debug_($setting, 'settings=null');
                $setting = new SettingsModel();
                $setting->module_id = $moduleId;
                $setting->param_name = $name;
            } // Если значение не изменилось то не сохраняем
            elseif ($setting->param_value == $value) {
                //debug_($setting, 'settings - старое значение');
                continue;
            }

            //debug_($setting->param_value);
            // Присваиваем новое значение
            $setting->param_value = $value;
            //debug($setting->param_value);
            //debug_($setting);
            // Добавляем для параметра его правила валидации
            //$setting->rulesFromModule = Yii::app()->getModule($moduleId)->getRulesForParam($name);

            //Сохраняем
            if (!$setting->save()) {
                return false;
            }
        }

        return true;
    }

}