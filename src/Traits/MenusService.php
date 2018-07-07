<?php
/** .-------------------------------------------------------------------
 * |      Site: www.hdcms.com  www.houdunren.com
 * |      Date: 2018/7/2 下午2:21
 * |    Author: 向军大叔 <2300071698@qq.com>
 * '-------------------------------------------------------------------*/
namespace Houdunwang\Module\Traits;

trait MenusService
{
    public function getMenus()
    {
        foreach (\Module::getOrdered() as $module) {
            $path                   = config('modules.paths.modules')."/{$module->name}/Config";
            $menus[\HDModule::config($module->name.'.config.name')] = include "{$path}/menus.php";
        }

        return $menus;
    }
}
