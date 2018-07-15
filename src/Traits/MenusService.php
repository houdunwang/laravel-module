<?php
/** .-------------------------------------------------------------------
 * |      Site: www.hdcms.com  www.houdunren.com
 * |      Date: 2018/7/2 下午2:21
 * |    Author: 向军大叔 <2300071698@qq.com>
 * '-------------------------------------------------------------------*/
namespace Houdunwang\Module\Traits;

trait MenusService
{
    /**
     * 获取所有菜单
     *
     * @return mixed
     */
    public function getMenus()
    {
        foreach (\Module::getOrdered() as $module) {
            $path          = config('modules.paths.modules')."/{$module->name}/Config";
            $menusConfig   = include "{$path}/menus.php";
            $title         = \HDModule::config($module->name.'.config.name');
            $menus[$title] = $menusConfig;
        }

        return $menus;
    }

    /**
     * 获取模块菜单
     *
     * @param $module
     *
     * @return mixed
     */
    public function getMenuByModule($module = null)
    {
        $module = $module ?? \HDModule::currentModule();
        $path = config('modules.paths.modules')."/{$module}/Config";

        return include "{$path}/menus.php";
    }
}
