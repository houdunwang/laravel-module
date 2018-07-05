<?php
/** .-------------------------------------------------------------------
 * |      Site: www.hdcms.com  www.houdunren.com
 * |      Date: 2018/7/2 下午2:21
 * |    Author: 向军大叔 <2300071698@qq.com>
 * '-------------------------------------------------------------------*/
namespace Houdunwang\Module\Traits;

use Module;

trait PermissionService
{
    public function getPermissionByGuard($guard)
    {
        $modules     = Module::getOrdered();
        $permissions = [];
        foreach ($modules as $module) {
            $permissions[] = [
                'module' => $module,
                'config' => $this->config($module->getName().'.config'),
                'rules'  => $this->filterByGuard($module, $guard),
            ];
        }

        return $permissions;
    }

    protected function filterByGuard($module, $guard)
    {
        $data = $config = \HDModule::config($module.'.permission');
        foreach ($config as $k => $group) {
            foreach ($group['permissions'] as $n => $permission) {
                if ($permission['guard'] != $guard) {
                    unset($data[$k]['permissions'][$n]);
                }
            }
        }
        return $data;
    }
}
