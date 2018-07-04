<?php
/** .-------------------------------------------------------------------
 * |      Site: www.hdcms.com  www.houdunren.com
 * |      Date: 2018/7/2 下午2:21
 * |    Author: 向军大叔 <2300071698@qq.com>
 * '-------------------------------------------------------------------*/
namespace Houdunwang\Module\Services;

use Module;

class PermissionService
{
    public static function all()
    {
        $modules     = Module::getOrdered();
        $permissions = [];
        foreach ($modules as $module) {
            $permissions[$module->getName()] = [
                'module'     => $module,
                'permission' => include $module->getPath().'/config/permission.php',
            ];
        }

        return $permissions;
    }

    protected function config()
    {

    }
}
