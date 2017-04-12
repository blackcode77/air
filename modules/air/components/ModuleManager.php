<?php
/**
 * Created by PhpStorm.
 * User: Blackcode
 * Date: 10.04.2017
 * Time: 22:04
 */

namespace air\components;


use yii\base\Component;

/**
 * Class ModuleManager
 * @package air\components
 */
class ModuleManager extends Component
{
    /**
     *
     */
    const CORE_MODULE = 'air';

    /**
     *
     */
    const INSTALL_MODULE = 'install';

    /**
     * @var
     */
    public $otherCategoryName;
    /**
     * @var
     */
    public $category;
    /**
     * @var
     */
    public $categoryIcon;
    /**
     * @var
     */
    public $categorySort;

    /**
     * Возвращаем список модулей:
     *
     * @param bool $navigationOnly - только навигация
     * @param bool $disableModule - отключённые модули
     *
     * @return mixed
     **/
    public function getModules($navigationOnly = false, $disableModule = false)
    {
        //var_dump($this->otherCategoryName); die;
        $this->otherCategoryName = 'Other';

        $this->categoryIcon = [
            'Services' => 'fa fa-fw fa-briefcase',
            'Air!' => 'fa fa-fw fa-cog',
            'Content' => 'fa fa-fw fa-file',
            $this->otherCategoryName => 'fa fa-fw fa-cog',
        ];

        $this->categorySort = [
             'Users',
             'Content',
             'Structure',
             'Users',
             'Services',
             'Air!',
             'Store',
            $this->otherCategoryName,//Other
        ];

        $modules = $yiiModules = $order = [];
        $modulesExtendedNavigation = [];

        if (count(\Yii::$app->getModules())) {
            /**
             *
             * Получаем модули и заполняем основные массивы
             **/

            foreach (\Yii::$app->getModules() as $key => $value) {
                $key = strtolower($key);
                $module = \Yii::$app->getModule($key);
                if ($module !== null) {
                    if ($module instanceof WebModule) {
                        $category = (!$module->getCategory())
                            ? $this->otherCategoryName
                            : $module->getCategory();
                        $modules[$key] = $module; //key = air
                        $order[$category][$key] = $module->adminMenuOrder; // $order[Air!][air] = -1
                        $moduleExNav = (array)$module->getExtendedNavigation(); // пусто
                        $modulesExtendedNavigation = array_merge($modulesExtendedNavigation, $moduleExNav);
                    } else {
                        $yiiModules[$key] = $module;
                    }
                }
            }

            $modulesNavigation =   false; // \Yii::$app->cache->get('YupeModulesNavigation');


            if ($modulesNavigation === false) {
                // Формируем навигационное меню
                $modulesNavigation = [];

                // Сортируем категории модулей
                if (count($order) > 1) { // =1 значит не заходим
                    $categorySort = array_reverse($this->categorySort);

                    foreach ($categorySort as $iValue) {
                        if (array_key_exists($iValue, $order)) {
                            $orderValue = $order[$iValue];
                            unset($order[$iValue]);
                            $order = array_merge([$iValue => $orderValue], $order);
                        }
                    }
                }

                $uniqueMenuId = 0;

                $settings['items'] = [];

                // Обходим категории модулей
                foreach ($order as $keyCategory => $valueCategory) { // $order[Air!][air] = -1
                                    // Air!      =>  -1

                    // Шаблон категорий
                    $modulesNavigation[$keyCategory] = [
                        'label' => $keyCategory,
                        //'url' => '#',
                        'items' => [],
                        'submenuOptions' => ["id" => "mainmenu_".$uniqueMenuId],
                    ];
                    $uniqueMenuId++;

                    if (array_key_exists($keyCategory, $this->categoryIcon)) {
                        $modulesNavigation[$keyCategory]['icon'] = $this->categoryIcon[$keyCategory];
                    }

                    // Сортируем модули в категории
                    asort($valueCategory, SORT_NUMERIC);

                    // Обходим модули
                    foreach ($valueCategory as $key => $value) {
                        $modSettings = [];
                        // Собраются подпункты категории "Настройки модулей", кроме пункта Юпи
                        if ($key !== self::CORE_MODULE && $modules[$key]->editableParams) {
                            $modSettings = [
                                '---',
                                [
                                    //'icon' => 'fa fa-fw fa-cog',
                                    'label' => 'Module settings',
                                    'url' => $modules[$key]->getSettingsUrl(),
                                ],
                            ];
                        }

                        // Проверка на вывод модуля в категориях, потребуется при отключении модуля
                        if (!$modules[$key]->getIsShowInAdminMenu()) {
                            continue;
                        }

                        // Если нет иконки для данной категории - подставляется иконка первого модуля
                        if (!isset($modulesNavigation[$keyCategory]['icon']) && $modules[$key]->icon) {
                            $modulesNavigation[$keyCategory]['icon'] = $modules[$key]->icon;
                        }

                        // Шаблон модулей
                        $data = [
                            //'icon' => $modules[$key]->icon,
                            'label' => $modules[$key]->name,
                            'url' => $modules[$key]->adminPageLinkNormalize,
                            'submenuOptions' => ["id" => "submenu_".$key],
                            'items' => [],
                        ];

                        // Добавляем подменю у модулей
                        $links = $modules[$key]->getNavigation(); // !!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!! исправить метод у air
                        if (!empty($links)) {
                            $data['items'] = $links;
                        } else {
                            unset($modSettings[0]);
                        }

                        if ($key !== self::CORE_MODULE) {
                            $data['items'] = array_merge(
                                $data['items'],
                                $key === self::CORE_MODULE ? [] : $modSettings
                            );
                        }

                        $modulesNavigation[$keyCategory]['items'][$modules[$key]->id] = $data;// !!!!!!! id
                    }
                }

                foreach ($modulesNavigation as $key => $data) {
                    if (count($data['items']) === 1) {
                        $items = array_shift($modulesNavigation[$key]['items']);
                        $modulesNavigation[$key]['items'] = $items['items'];
                    }
                }


                /*// Цепочка зависимостей:
                $chain = new CChainedCacheDependency();

                // Зависимость на тег:
                $chain->dependencies->add(
                    new TagsCache('yupe', 'navigation', 'installedModules')
                );

                // Зависимость на каталог 'application.config.modules':
                $chain->dependencies->add(
                    new CDirectoryCacheDependency(
                        Yii::getPathOfAlias('application.config.modules')
                    )
                );*/

                \Yii::$app->cache->set('YupeModulesNavigation', $modulesNavigation, 1000);


                /*Yii::app()->getCache()->set(
                    'YupeModulesNavigation-'.Yii::app()->getLanguage(),
                    $modulesNavigation,
                    0,
                    $chain
                );*/
            }
        }

        /*// Подгрузка отключенных модулей
        if ($disableModule) {
            $modules = array_merge((array)$this->getModulesDisabled($modules), $modules);
        }*/

        $modulesNavigation = array_merge($modulesNavigation, $modulesExtendedNavigation);

        return ($navigationOnly === true) ? $modulesNavigation : [
            'modules' => $modules,
            'yiiModules' => $yiiModules,
            'modulesNavigation' => $modulesNavigation,
        ];
    }
}

