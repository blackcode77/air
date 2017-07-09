<?php
/**
 * Created by PhpStorm.
 * User: Blackcode
 * Date: 17.04.2017
 * Time: 23:15
 */

namespace app\modules\user\components;


use app\models\User;
use yii\db\ActiveRecord;
use yii\web\IdentityInterface;
use yii\filters\AccessControl;

class IdentityUser extends ActiveRecord implements IdentityInterface
{
    /**
     * `id`,
     * `update_time`,
     * `first_name`,
     * `middle_name`,
     * `last_name`,
     * `nick_name`,
     * `email`,
     * `gender`,
     * `birth_date`,
     * `site`,
     * `about`,
     * `location`,
     * `status`,
     * `access_level`,
     * `visit_time`,
     * `create_time`,
     * `avatar`,
     * `hash`,      !!!
     * `email_confirm`,
     * `phone`
     */

   
    
    
    public static function tableName(){
        return '{{%user_user}}';

    }

    public static function findIdentity($id)
    {
        return static::findOne($id);
    }

    public static function findIdentityByAccessToken($token, $type = null)
    {
        return static::findOne(['hash' => $token]);
    }

    public function getId()
    {
        return $this->id;
    }

    public function getAuthKey()
    {
        return $this->hash;
    }

    public function validateAuthKey($authKey)
    {
        return true;//$this->getAuthKey() === $authKey;
    }




}