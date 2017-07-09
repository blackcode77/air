<?php
/**
 * Created by PhpStorm.
 * User: Blackcode
 * Date: 09.04.2017
 * Time: 16:39
 */

namespace air\components;


use yii\base\Module;
use yii\db\Exception;

class WebModule extends Module
{
    /**
     *
     */
    const CHECK_ERROR = 'danger';
    /**
     *
     */
    const CHECK_NOTICE = 'warning';

    /**
     *
     */
    const CHOICE_YES = 1;
    /**
     *
     */
    const CHOICE_NO = 0;

    /**
     * @var integer категория для контента модуля
     * @since 0.6
     */
    public $mainCategory;

    /**
     * @var str каталог с документацией внутри модуля
     * @since 0.5.1
     */
    public $docPath = 'guide';

    /**
     * @var int порядок следования модуля в меню панели управления (сортировка)
     */
    public $adminMenuOrder = 0;
    /**
     * @var int некоторые компоненты Юпи! автоматически кэширует, если время жизни кэша не указано - берется это значение
     */
    public $coreCacheTime = 3600;

    /**
     * @var string - id редактора
     */
    public $editor = 'redactor';

    /**
     * @var null|string - класс редактора
     */
    protected $visualEditor = null;

    /**
     * @var array - массив редакторов
     */
    public $visualEditors = [
        'redactor' => [
            'class' => 'yupe\widgets\editors\Redactor',
        ],
    ];
    /**
     * @var bool | string
     *
     * Имя модели, которая является профилем пользователя для конкретного модуля
     *
     */
    public $profileModel = false;

    /**
     * @var int
     * @since 0.7
     *
     * Максимальный размер загружаемых файлов - 5 MB
     */
    public $maxSize = 5242880;

    /**
     * @var string
     * @since 0.7
     *
     * Разрешенные mime types файлов для загрузки
     *
     */
    public $mimeTypes = 'image/gif,image/jpeg,image/png,application/pdf,application/msword,application/vnd.openxmlformats-officedocument.wordprocessingml.document, application/zip,application/x-rar,application/x-rar-compressed, application/vnd.ms-excel, application/vnd.openxmlformats-officedocument.spreadsheetml.sheet';

    /**
     * @var string
     * @since 0.7
     *
     * Разрешенные расширения файлов для загрузки
     */

    public $allowedExtensions = 'gif, jpeg, png, jpg, zip, rar, doc, docx, xls, xlsx, pdf';

    /**
     * Путь к ресурсам модуля, например application.modules.yupe.views.assets
     * @var string
     */
    public $assetsPath;
    /**
     * @var
     */
    private $_assetsUrl;

    /**
     * Инициализация модуля, считывание настроек из базы данных и их кэширование
     *
     * @return void
     */
    public function init()
    {
        parent::init();
        $this->getSettings();
        //debug_($this);
    }

    /**
     * Получаем настройки модуля:
     *
     * @param boolean $needReset необходимо ли сбросить настройки
     *
     * @return void
     */
    public function getSettings($needReset = false)
    {


       // try {
            //debug_(\Yii::$app->db);
            $settingsRows = \Yii::$app->db
                //->cache($this->coreCacheTime, new \TagsCache($this->getId(), 'settings'))
                ->createCommand(
                    '
                    SELECT param_name, param_value
                        FROM {{%yupe_settings}}
                        WHERE module_id = :module_id AND type = :type
                    '
                )
                ->bindValue(':module_id', $this->getId()) //TODO сейчас модуль вставлен костылем youpe, потом заменить на $this->getId()
                ->bindValue(':type', 1)
                ->queryAll();

            if (!empty($settingsRows)) {

                foreach ($settingsRows as $sRow) {
                    //debug($settingsRows);
                    if ( $this->hasProperty($sRow['param_name'])  //property_exists($this, $sRow['param_name'])
                    ) {

                        $this->{$sRow['param_name']} = $sRow['param_value'];

                    }
                }
            }

            return true;

        /*} catch (  Exception $e) {
            return false;
        }*/
    }


    public function getId(){
        return $this->getUniqueId();
    }

    /**
     * ссылка которая будет отображена в панели управления
     * как правило, ведет на страничку для администрирования модуля
     *
     * @return string
     */
    public function getAdminPageLink()
    {
        return '/' . strtolower($this->id) . '/' . strtolower($this->defaultController) . '/index';
    }

    /**
     * ссылка которая будет отображена в панели управления
     * как правило, ведет на страничку для администрирования модуля
     *
     * @return string
     */
    public function getAdminPageLinkNormalize()
    {
        return is_array($this->adminPageLink) ? $this->adminPageLink : [$this->adminPageLink];
    }

    /**
     * название иконки для меню админки, например 'user'
     *
     * @return string
     */
    public function getIcon()
    {
        return null;
    }

    /**
     * показать или нет модуль в панели управления
     *
     * @return bool
     */
    public function getIsShowInAdminMenu()
    {
        return true;
    }

    /**
     * @since 0.8
     * Возвращает Урл для странички настроек модуля
     * Для того чтобы можно было переопределить
     */
    public function getSettingsUrl()
    {
        return ['/air/backend/modulesettings', 'module' => $this->getId()];
    }
    /**
     *  метод-хелпер именно для многих параметров модуля, где
     *  необходимо вывести варианты выбора да или нет
     *
     * @return array для многих параметров модуля необходимо вывести варианты выбора да или нет - метод-хелпер именно для этого
     */
    public function getChoice()
    {
        return [
            self::CHOICE_YES => 'да',
            self::CHOICE_NO  => 'нет',
        ];
    }

    /**
     * массив параметров модуля, которые можно редактировать через панель управления (GUI)
     *
     * @return array
     */
    public function getEditableParams()
    {
        return ['adminMenuOrder', 'coreCacheTime'];
    }

    /**
     * массив лейблов для параметров (свойств) модуля. Используется на странице настроек модуля в панели управления.
     *
     * @return array
     */
    public function getDefaultParamsLabels()
    {
        return [
            'adminMenuOrder' => 'Menu items order',
            'coreCacheTime'  => 'Cache time',
        ];

    }
    /**
     * получение имен параметров из getEditableParams()
     *
     * @return array
     */
    public function getEditableParamsKey()
    {
        $keyParams = [];
        foreach ($this->getEditableParams() as $key => $value) {
            $keyParams[] = is_int($key) ? $value : $key;
        }

        return $keyParams;
    }
    /**
     * массив лейблов для параметров (свойств) модуля. Используется на странице настроек модуля в панели управления.
     *
     * @return array
     */
    public function getParamsLabels()
    {
        return [
            'adminMenuOrder' =>  'Menu items order',
            'coreCacheTime'  =>  'Cache time',
        ];

    }
    /**
     * массив групп параметров модуля, для группировки параметров на странице настроек
     *
     * @return array
     */
    public function getEditableParamsGroups()
    {
        return [
            'main' => [
                'label' => 'Главные настройки модуля',
                'items' => [
                    'adminMenuOrder',
                    'coreCacheTime'
                ]
            ],
        ];
    }


    /**
     * если модуль должен добавить несколько ссылок в панель управления - укажите массив
     *
     * @return array
     *
     * @example
     *
     * public function getNavigation()
     * {
     *       return array(
     *           Yii::t('YupeModule.yupe','Blogs')     => '/blog/blogBackend/admin/',
     *           Yii::t('YupeModule.yupe','Posts')    => '/blog/postBackend/admin/',
     *           Yii::t('YupeModule.yupe','Members') => '/blog/BlogToUserBackend/admin/',
     *      );
     * }
     *
     */
    public function getNavigation()
    {
        return [];
    }

    /**
     * Расширенное меню модуля, формат такой же, как и у {@see getNavigation()}
     * @return array
     */
    public function getExtendedNavigation()
    {
        return [];
    }

    /**
     * каждый модуль должен принадлежать одной категории, именно по категориям делятся модули в панели управления
     *
     * @return string
     */
    public function getCategory()
    {
        return null;
    }

    /**
     * порядок следования модуля в меню панели управления (сортировка)
     *
     * @return int
     */
    public function getAdminMenuOrder()
    {
        return $this->adminMenuOrder;
    }
}