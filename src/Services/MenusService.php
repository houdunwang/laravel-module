<?php
/** .-------------------------------------------------------------------
 * |      Site: www.hdcms.com  www.houdunren.com
 * |      Date: 2018/7/2 下午2:21
 * |    Author: 向军大叔 <2300071698@qq.com>
 * '-------------------------------------------------------------------*/
namespace Houdunwang\Module\Services;

class MenusService
{
    public function all()
    {
        foreach (\Module::getOrdered() as $module) {
            $path                   = config('modules.paths.modules')."/{$module->name}/Config";
            $config                 = include "{$path}/config.php";
            $menus[$config['name']] = include "{$path}/menus.php";
        }

        return $menus;
    }
}
