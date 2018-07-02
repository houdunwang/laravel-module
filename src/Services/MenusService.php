<?php
/** .-------------------------------------------------------------------
 * |      Site: www.hdcms.com  www.houdunren.com
 * |      Date: 2018/7/2 下午2:21
 * |    Author: 向军大叔 <2300071698@qq.com>
 * '-------------------------------------------------------------------*/
namespace Houdunwang\Module\Services;

use Houdunwang\Module\Traits\BaseTrait;

class MenusService
{
    use BaseTrait;

    public function all()
    {
        $menus   = [];
        $modules = glob(config('modules.paths.modules').'/*');
        foreach ($modules as $module) {
            $name                   = basename($module);
            $path                   = config('modules.paths.modules')."/{$name}/Config";
            $config                 = include "{$path}/config.php";
            $menus[$config['name']] = include "{$path}/menus.php";
        }

        return $menus;
    }
}
