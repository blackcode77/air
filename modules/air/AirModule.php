<?php
/**
 * Created by PhpStorm.
 * User: Blackcode
 * Date: 09.04.2017
 * Time: 16:30
 */
namespace app\modules\air;



use air\components\WebModule;
use yii\helpers\Html;

class AirModule extends WebModule
{
    /**
     *
     */
    const VERSION = '1.1';

    /**
     * @var
     */
    public $enableAssets;
    /**
     * @var
     */
    public $cache;

    /**
     * @var
     */
    public $siteDescription;
    /**
     * @var
     */
    public $siteName;
    /**
     * @var
     */
    public $siteKeyWords;

    /**
     * @var string
     */
    public $backendLayout = 'column2';
    /**
     * @var
     */
    public $backendTheme;
    /**
     * @var string
     */
    public $emptyLayout = 'empty';
    /**
     * @var
     */
    public $theme;

    /**
     * @var int
     */
    public $coreCacheTime = 3600;
    /**
     * @var string
     */
    public $coreModuleId = 'yupe';

    /**
     * @var string
     */
    public $uploadPath = 'uploads';
    /**
     * @var
     */
    public $email;

    /**
     * @var string
     */
    public $availableLanguages = 'ru,uk_ua,en,zh_cn';
    /**
     * @var string
     */
    public $defaultLanguage = 'ru';
    /**
     * @var string
     */
    public $defaultBackendLanguage = 'ru';

    /**
     * @var int
     */
    public $adminMenuOrder = -1;

    /**
     * @var string
     */
    public $profileModel = 'User';

    /**
     * @var
     */
    public $allowedIp;
    /**
     * @var int
     */
    public $hidePanelUrls = 0;

    /**
     * @var string
     */
    public $logo = 'images/logo.png';

    /**
     * Возвращаем линк на админ панель:
     *
     * @return mixed
     **/
    public function getAdminPageLink()
    {
        return '/yupe/backend/settings';
    }

    /**
     * Возвращаем название модуля:
     *
     * @return string
     **/
    public function getName()
    {
        return 'Air!';
    }

    /**
     * Возвращаем иконку модуля:
     *
     * @return string
     **/
    public function getIcon()
    {
        return "fa fa-fw fa-cog";
    }

    /**
     * Возвращаем названия параметров:
     *
     * @return mixed
     **/
    public function getParamsLabels()
    {
        return [
            'siteDescription' => 'Site description',
            'siteName' => 'Site title',
            'siteKeyWords' => 'Site keywords',
            'backendLayout' =>  'Layout of backend',
            'backendTheme' =>'Theme of backend',
            'theme' =>  'Frontend theme',
            'coreCacheTime' =>  'Chacing time (sec.)',
            'uploadPath' => 'File uploads catalog (relative to the site root)',
            'editor' =>  'Visual editor',
            'email' =>  'Admin Email',
            'availableLanguages' => 'List of available languages (for example. ru,en,de)',
            'defaultLanguage' => 'Default language',
            'defaultBackendLanguage' => 'Default backend language',
            'allowedIp' => 'Allowed IP',
            'hidePanelUrls' =>  'Hide panel urls',
            'logo' => 'Logo',
            'allowedExtensions' =>  'Allowed extensions (separated by comma)',
            'mimeTypes' =>  'Mime types',
            'maxSize' => 'Maximum size (in bytes)',

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
            'site' => [
                'label' =>  'Site settings',
                'items' => [
                    'logo',
                    'siteName',
                    'siteDescription',
                    'siteKeyWords',
                ],
            ],
            'theme' => [
                'label' => 'Themes',
                'items' => [
                    'theme',
                    'backendTheme',
                ],
            ],
            /*'language' => [
                'label' => Yii::t('YupeModule.yupe', 'Language settings'),
                'items' => [
                    'availableLanguages',
                    'defaultLanguage',
                    'defaultBackendLanguage',
                ],
            ],
            'editors' => [
                'label' => Yii::t('YupeModule.yupe', 'Visual editors settings'),
                'items' => [
                    'editor',
                    'uploadPath',
                    'allowedExtensions',
                    'mimeTypes',
                    'maxSize',
                ],
            ],*/
            'main' => [
                'label' => 'Main settings admin panel',
                'items' => [
                    'hidePanelUrls',
                    'allowedIp',
                    'email',
                    'coreCacheTime',
                ],
            ],
        ];
    }
    /**
     * Возвращаем редактируемые параметры:
     *
     * @return mixed
     **/
    public function getEditableParams()
    {
        return [
            'coreCacheTime',
            'theme' => ['default'=>'default', 'shop'=>'shop'],
            //'backendTheme' => $this->getThemes(true),
            'siteName',
            'siteDescription',
            'siteKeyWords',
            'uploadPath',
            //'editor' => $this->getEditors(),
            'email',
            //'availableLanguages',
            //'defaultLanguage' => $this->getLanguagesList(),
            //'defaultBackendLanguage' => $this->getLanguagesList(),
            'allowedIp',
            //'hidePanelUrls' => $this->getChoice(),
            'logo',
            'allowedExtensions',
            'mimeTypes',
            'maxSize',
        ];
    }

    /**
     * Возвращаем массив меню:
     *
     * @return mixed
     **/
    public function getNavigation()
    {
        return [
            [
                'label' => 'Clean cache!',
                'url' => ['/yupe/backend/ajaxflush', 'method' => 1],
                'linkOptions' => [
                    'class' => 'flushAction',
                    'method' => 'cacheAll',
                ],
                //'icon' => 'fa fa-fw fa-trash-o',
                'items' => [
                    [
                        //'icon' => 'fa fa-fw fa-trash-o',
                        'label' =>  'Clean cache',
                        'url' => ['/yupe/backend/ajaxflush', 'method' => 1],
                        'linkOptions' => [
                            'class' => 'flushAction',
                            'method' => 'cacheFlush',
                        ],
                    ],
                    [
                        //'icon' => 'fa fa-fw fa-trash-o',
                        'label' => 'Clean settings cache',
                        'url' => ['/yupe/backend/flushDumpSettings'],
                        'linkOptions' => [
                            'class' => 'flushAction',
                            'method' => 'cacheFlush',
                        ],
                    ],
                    [
                        //'icon' => 'fa fa-fw fa-trash-o',
                        'label' => 'Clean assets',
                        'url' => ['/yupe/backend/ajaxflush', 'method' => 2],
                        'linkOptions' => [
                            'class' => 'flushAction',
                            'method' => 'assetsFlush',
                        ],
                        'visible' => 1,
                    ],
                    [
                        //'icon' => 'fa fa-fw fa-trash-o',
                        'label' => 'Clean cache and assets',
                        'url' => ['/yupe/backend/ajaxflush', 'method' => 3],
                        'linkOptions' => [
                            'class' => 'flushAction',
                            'method' => 'cacheAssetsFlush',
                        ],
                        'visible' => 0,
                    ],
                ],
            ],
            [
                //'icon' => "fa fa-fw fa-th",
                'label' => 'My modules',
                'url' => ['/yupe/backend/settings'],
            ],
            [
                //'icon' => 'fa fa-fw fa-picture-o',
                'label' => 'Theme settings',
                'url' => ['/yupe/backend/themesettings'],
            ],
            [
                //'icon' => 'fa fa-fw fa-wrench',
                'label' => 'Настройки сайта',
                'url' => $this->getSettingsUrl(),
            ],
            [
                //'icon' => 'fa fa-fw fa-shopping-cart',
                'label' =>'Air! store',
                'url' => 'https://yupe.ru/store?from=panel-yupe-store',
                'linkOptions' => [
                    'target' => '_blank'
                ]
            ],
            [
                //'icon' => "fa fa-fw fa-question-circle",
                'label' => 'About Yupe!',
                'url' => ['/yupe/backend/help'],
            ],
        ];
    }


    /**
     * Возвращаем название категории модуля:
     *
     * @return string
     **/
    public function getCategory()
    {
        return 'Система!';
    }

    /**
     * Возвращаем версию:
     *
     * @return string
     **/
    public function getVersion()
    {
        return self::VERSION;
    }

    /**
     * Генерация анкора PoweredBy
     *
     * @param string $color - цвет
     * @param string $text - текст
     *
     * @return string poweredBy
     */
    public function poweredBy($color = 'yellow', $text = '')
    {
        if (empty($text)) {
            $text = 'Powered by Air!';
        }

        return $text;
    }


    public function getBackendLayoutAlias($layoutName = '')
    {
        /*if ($this->backendTheme) {
            return 'themes.backend_'.$this->backendTheme.'.views.yupe.layouts.'.($layoutName ? $layoutName : $this->backendLayout);
        } else {*/
            //return 'application.modules.yupe.views.layouts.'.($layoutName ? $layoutName : $this->backendLayout);
        return "@app/modules/air/views/layouts/". ($layoutName ? $layoutName : $this->backendLayout);

    }

}