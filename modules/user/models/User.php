<?php
/**
 * Created by PhpStorm.
 * User: Blackcode
 * Date: 19.04.2017
 * Time: 12:13
 */

namespace app\modules\models;


use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;

class User extends ActiveRecord
{
    /**
     *
     */
    const GENDER_THING = 0;
    /**
     *
     */
    const GENDER_MALE = 1;
    /**
     *
     */
    const GENDER_FEMALE = 2;

    /**
     *
     */
    const STATUS_BLOCK = 0;
    /**
     *
     */
    const STATUS_ACTIVE = 1;
    /**
     *
     */
    const STATUS_NOT_ACTIVE = 2;

    /**
     *
     */
    const EMAIL_CONFIRM_NO = 0;
    /**
     *
     */
    const EMAIL_CONFIRM_YES = 1;

    /**
     *
     */
    const ACCESS_LEVEL_USER = 0;
    /**
     *
     */
    const ACCESS_LEVEL_ADMIN = 1;

    /**
     * @var
     */
    private $_oldAccess_level;
    /**
     * @var
     */
    private $_oldStatus;
    /**
     * @var bool
     */
    public $use_gravatar = false;

    /**
     * @return string the associated database table name
     */
    public function tableName()
    {
        return '{{%user_user}}';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules()
    {
        //$module = Yii::app()->getModule('user');

        return [
            [
                'birth_date, site, about, location, nick_name, first_name, last_name, middle_name, email',
                'filter',
                'filter' => 'trim'
            ],
            [
                'birth_date, site, about, location, nick_name, first_name, last_name, middle_name, email',
                'filter',
                //'filter' => [$obj = new CHtmlPurifier(), 'purify']
            ],
            ['nick_name, email, hash', 'required'],
            ['first_name, last_name, middle_name, nick_name, email', 'length', 'max' => 50],
            ['hash', 'length', 'max' => 256],
            ['site', 'length', 'max' => 100],
            ['about', 'length', 'max' => 300],
            ['location', 'length', 'max' => 150],
            ['gender, status, access_level', 'numerical', 'integerOnly' => true],
            ['gender', 'default', 'value' => self::GENDER_THING, 'setOnEmpty' => true],
            [
                'nick_name',
                'match',
                'pattern' => '/^[A-Za-z0-9_-]{2,50}$/',
                'message' => 'Bad field format for "{attribute}". You can use only letters and digits from 2 to 20 symbols'

            ],
            ['site', 'url', 'allowEmpty' => true],
            ['email', 'email'],
            ['email', 'unique', 'message' =>  'This email already use by another user'],
            [
                'nick_name',
                'unique',
                'message' => 'This nickname already use by another user',
            ],
            [
                'avatar',
                'file',
                //'types' => $module->avatarExtensions,
                //'maxSize' => $module->avatarMaxSize,
                'allowEmpty' => true,
                'safe' => false
            ],
            //['email_confirm', 'in', 'range' => array_keys($this->getEmailConfirmStatusList())],
           // ['status', 'in', 'range' => array_keys($this->getStatusList())],
            ['create_time', 'length', 'max' => 50],
            [
                'id, update_time, create_time, middle_name, first_name, last_name, nick_name, email, gender, avatar, status, access_level, visit_time, phone',
                'safe',
                'on' => 'search'
            ],
            ['birth_date', 'default', 'setOnEmpty' => true, 'value' => null],
            [
                'phone',
                'match',
                //'pattern' => $module->phonePattern,
                'message' => 'Некорректный формат поля {attribute}'
            ],
        ];
    }

    /**
     * @return array
     */
    public function behaviors()
    {
        return [
            [
                'class' => TimestampBehavior::className(),
                'createdAtAttribute' => 'create_time',
                'updatedAtAttribute' => 'update_time',
                'value' => new Expression('NOW()'),
            ],
        ];
    }

    /**
     * Массив связей:
     *
     * @return array
     */
    /*public function relations()
    {
        return [
            'tokens' => [
                self::HAS_MANY,
                'UserToken',
                'user_id'
            ]
        ];
    }*/

    /**
     * @return array customized attribute labels (name=>label)
     */
    public function attributeLabels()
    {
        return [
            'id' =>  'Id',
            'creation_date' =>  'Дата активации',
            'update_time' => 'Обновлен в',
            'first_name' => 'Имя',
            'last_name' => 'Фамилия',
            'middle_name' => 'Отчество',
            'full_name' => 'Полное имя',
            'nick_name' => 'Ник',
            'email' => 'E-mail',
            'gender' => 'Пол',
            'status' => 'Статус',
            'access_level' => 'Доступ',
            'visit_time' =>  'Последний визит',
            'create_time' => 'Время создания',
            'avatar' => 'Аватар',
            'use_gravatar' => 'Граватар',
            'email_confirm' => 'E-mail был подтвержден',
            'birth_date' =>  'День рождения',
            'site' => 'Site/blog',
            'location' => 'Location',
            'about' => 'О себе',
            'phone' => 'Телефон',
        ];
    }

    /**
     * Проверка верификации почты:
     *
     * @return boolean
     */
    public function getIsVerifyEmail()
    {
        return $this->email_confirm;
    }

    /**
     * Строковое значение верификации почты пользователя:
     *
     * @return string
     */
    public function getIsVerifyEmailStatus()
    {
        return $this->getIsVerifyEmail()
            ? 'Да'
            : 'Нет';
    }

    /**
     * Поиск пользователей по заданным параметрам:
     *
     * @return CActiveDataProvider
     */
   /* public function search($pageSize = 10)
    {
        $criteria = new CDbCriteria();

        $criteria->compare('t.id', $this->id);
        $criteria->compare('t.update_time', $this->update_time, true);
        if ($this->create_time) {
            $criteria->compare('t.create_time', date('Y-m-d', strtotime($this->create_time)), true);
        }
        $criteria->compare('t.first_name', $this->first_name, true);
        $criteria->compare('t.middle_name', $this->middle_name, true);
        $criteria->compare('t.last_name', $this->last_name, true);
        $criteria->compare('t.nick_name', $this->nick_name, true);
        $criteria->compare('t.email', $this->email, true);
        $criteria->compare('t.phone', $this->phone, true);
        $criteria->compare('t.gender', $this->gender);
        $criteria->compare('t.status', $this->status);
        $criteria->compare('t.access_level', $this->access_level);
        if ($this->visit_time) {
            $criteria->compare('t.visit_time', date('Y-m-d', strtotime($this->visit_time)), true);
        }
        $criteria->compare('t.email_confirm', $this->email_confirm);

        return new CActiveDataProvider(get_class($this), [
            'criteria' => $criteria,
            'pagination' => [
                'pageSize' => $pageSize,
            ],
            'sort' => [
                'defaultOrder' => 'visit_time DESC',
            ]
        ]);
    }*/

    /**
     * Метод после поиска:
     *
     * @return void
     */
   /* public function afterFind()
    {
        $this->_oldAccess_level = $this->access_level;
        $this->_oldStatus = $this->status;
        // Если пустое поле аватар - автоматически
        // включаем граватар:
        $this->use_gravatar = empty($this->avatar);

        parent::afterFind();
    }*/

    /**
     * Метод выполняемый перед сохранением:
     *
     * @return bool
     */
    /*public function beforeSave()
    {
        if (!$this->getIsNewRecord() && $this->_oldAccess_level === self::ACCESS_LEVEL_ADMIN) {
            // Запрещаем действия, при которых администратор
            // может быть заблокирован или сайт останется без
            // администратора:
            if (
                $this->admin()->count() == 1
                && ((int)$this->access_level === self::ACCESS_LEVEL_USER || (int)$this->status !== self::STATUS_ACTIVE)
            ) {
                $this->addError(
                    'access_level',
                    Yii::t('UserModule.user', 'You can\'t make this changes!')
                );

                return false;
            }
        }

        return parent::beforeSave();
    }*/


    /**
     * Метод перед удалением:
     *
     * @return bool
     */
    /*public function beforeDelete()
    {
        if ($this->_oldAccess_level == self::ACCESS_LEVEL_ADMIN && $this->admin()->count() == 1) {
            $this->addError(
                'access_level',
                Yii::t('UserModule.user', 'You can\'t make this changes!')
            );

            return false;
        }

        return parent::beforeDelete();
    }*/

    /**
     * Именнованные условия:
     *
     * @return array
     */
    public function scopes()
    {
        return [
            'active' => [
                'condition' => 't.status = :user_status',
                'params' => [
                    ':user_status' => self::STATUS_ACTIVE
                ],
            ],
            'registered' => [
                'condition' => 't.status = :user_status',
                'params' => [
                    ':user_status' => self::STATUS_NOT_ACTIVE
                ],
            ],
            'blocked' => [
                'condition' => 'status = :blocked_status',
                'params' => [':blocked_status' => self::STATUS_BLOCK],
            ],
            'admin' => [
                'condition' => 'access_level = :access_level',
                'params' => [':access_level' => self::ACCESS_LEVEL_ADMIN],
            ],
            'user' => [
                'condition' => 'access_level = :access_level',
                'params' => [':access_level' => self::ACCESS_LEVEL_USER],
            ],
        ];
    }

    /**
     * Список текстовых значений ролей:
     *
     * @return array
     */
    public function getAccessLevelsList()
    {
        return [
            self::ACCESS_LEVEL_ADMIN =>  'Администратор',
            self::ACCESS_LEVEL_USER => 'Пользователь',
        ];
    }

    /**
     * Получаем строковое значение роли
     * пользователя:
     *
     * @return string
     */
    public function getAccessLevel()
    {
        $data = $this->getAccessLevelsList();

        return isset($data[$this->access_level]) ? $data[$this->access_level] : '*no*';
    }

    /**
     * Список возможных статусов пользователя:
     *
     * @return array
     */
    public function getStatusList()
    {
        return [
            self::STATUS_ACTIVE => 'Активный',
            self::STATUS_BLOCK =>  'Заблокированны',
            self::STATUS_NOT_ACTIVE => 'Not activated',
        ];
    }

    /**
     * Получение строкового значения
     * статуса пользователя:
     *
     * @return string
     */
    public function getStatus()
    {
        $data = $this->getStatusList();

        return isset($data[$this->status])
            ? $data[$this->status]
            : 'статус не присвоен';
    }

    /**
     * @return array
     */
    public function getEmailConfirmStatusList()
    {
        return [
            self::EMAIL_CONFIRM_YES => 'Да',
            self::EMAIL_CONFIRM_NO => 'Нет',
        ];
    }

    /**
     * @return string
     */
    public function getEmailConfirmStatus()
    {
        $data = $this->getEmailConfirmStatusList();

        return isset($data[$this->email_confirm]) ? $data[$this->email_confirm] : '*unknown*';
    }

    /**
     * Список статусов половой принадлежности:
     *
     * @return array
     */
    public function getGendersList()
    {
        return [
            self::GENDER_FEMALE =>  'жен'),
            self::GENDER_MALE => 'муж'),
            self::GENDER_THING => 'нет данных'),
        ];
    }

    /**
     * Получаем строковое значение половой
     * принадлежности пользователя:
     *
     * @return string
     */
    public function getGender()
    {
        $data = $this->getGendersList();

        return isset($data[$this->gender])
            ? $data[$this->gender]
            : $data[self::GENDER_THING];
    }

    /**
     * Получить url аватарки пользователя:
     * -----------------------------------
     * Возвращаем именно url, так как на
     * фронте может быть любая вариация
     * использования, незачем ограничивать
     * разработчиков.
     *
     * @param int $size - требуемый размер аватарки в пикселях
     *
     * @return string - url аватарки
     */
    /*public function getAvatar($size = 64)
    {
        $size = (int)$size;

        $userModule = Yii::app()->getModule('user');

        // если это граватар
        if ($this->use_gravatar && $this->email) {
            return 'http://gravatar.com/avatar/' . md5(trim($this->email)) . "?s=" . $size . "&d=" . urlencode(
                Yii::app()->createAbsoluteUrl('/') . $userModule->getDefaultAvatar()
            );
        }

        $avatar = $this->avatar;
        $path = $userModule->getUploadPath();

        if (!file_exists($path)) {
            $avatar = $userModule->defaultAvatar;
        }

        return Yii::app()->thumbnailer->thumbnail(
            $path . $avatar,
            $userModule->avatarsDir,
            $size,
            $size
        );
    }*/

    /**
     * Получаем список пользователей с полным имем:
     *
     * @param string $separator - разделитель
     *
     * @return string
     */
    public static function getFullNameList($separator = ' ')
    {
        debug_('User:getFullNameList');
        /*$list = [];

        foreach (User::model()->cache(Yii::app()->getModule('yupe')->coreCacheTime)->findAll() as $user) {
            $list[$user->id] = $user->getFullName($separator);
        }

        return $list;*/
    }

    /**
     * Получаем полное имя пользователя:
     *
     * @param string $separator - разделитель
     *
     * @return string
     */
    public function getFullName($separator = ' ')
    {
        return ($this->first_name || $this->last_name)
            ? $this->last_name . $separator . $this->first_name . ($this->middle_name ? ($separator . $this->middle_name) : "")
            : $this->nick_name;
    }

    /**
     * Удаление старого аватара:
     *
     * @return boolean
     */
    public function removeOldAvatar()
    {
        debug_('removeOldAvatar');
        /*if (!$this->avatar) {
            return true;
        }

        $basePath = Yii::app()->getModule('user')->getUploadPath();

        if (file_exists($basePath . $this->avatar)) {
            @unlink($basePath . $this->avatar);
        }

        //remove old resized avatars
        foreach (glob($basePath . '/thumbs/' . '*' . $this->avatar) as $thumb) {
            @unlink($thumb);
        }

        $this->avatar = null;

        return true;*/
    }

    /**
     * Устанавливает новый аватар
     *
     * @param CUploadedFile $uploadedFile
     *
     * @throws CException
     *
     * @return boolean
     */
    public function changeAvatar(//CUploadedFile 
                                 $uploadedFile)
    {
        debug_('changeAvatar');
        /*$basePath = Yii::app()->getModule('user')->getUploadPath();

        //создаем каталог для аватарок, если не существует
        if (!is_dir($basePath) && !@mkdir($basePath, 0755, true)) {
            throw new CException(Yii::t('UserModule.user', 'It is not possible to create directory for avatars!'));
        }

        $filename = $this->id . '_' . time() . '.' . $uploadedFile->extensionName;

        $this->removeOldAvatar();

        if (!$uploadedFile->saveAs($basePath . $filename)) {
            throw new CException(Yii::t('UserModule.user', 'It is not possible to save avatar!'));
        }

        $this->use_gravatar = false;

        $this->avatar = $filename;

        return true;*/
    }

    /**
     * @return bool
     */
    public function isActive()
    {
        return (int)$this->status === self::STATUS_ACTIVE;
    }

    /**
     * @return $this
     */
    public function activate()
    {
        \yii\web\User::
        $this->status = self::STATUS_ACTIVE;
        $this->email_confirm = self::EMAIL_CONFIRM_YES;
        return $this;
    }
}