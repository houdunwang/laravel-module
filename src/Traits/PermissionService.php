<?php
/** .-------------------------------------------------------------------
 * |      Site: www.hdcms.com  www.houdunren.com
 * |      Date: 2018/7/2 下午2:21
 * |    Author: 向军大叔 <2300071698@qq.com>
 * '-------------------------------------------------------------------*/
namespace Houdunwang\Module\Traits;

use Module;
use Spatie\Permission\Models\Permission;

/**
 * Trait PermissionService
 *
 * @package Houdunwang\Module\Traits
 */
trait PermissionService
{
    /**
     * 验证权限
     *
     * @param        $permissions
     * @param string $guard
     *
     * @return bool
     */
    public function hadPermission($permissions, string $guard): bool
    {
        if ($this->isWebMaster()) {
            return true;
        }
        $permissions = is_array($permissions) ? $permissions : [$permissions];
        $data        = array_filter($permissions, function ($permission) use ($guard) {
            $where = [
                ['name', '=', $permission],
                ['guard_name', '=', $guard],
            ];

            return (bool)\DB::table('permissions')->where($where)->first();
        });

        return auth()->user()->hasAnyPermission($data);
    }

    /**
     * 站长检测
     *
     * @return bool
     */
    public function isWebMaster($guard = 'admin'): bool
    {
        $relation = auth($guard)->user()->roles();
        $has      = $relation->where('roles.name', config('hd_module.webmaster'))->first();

        return boolval($has);
    }

    /**
     * @param $guard
     *
     * @return array
     */
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

    /**
     * @param $module
     * @param $guard
     *
     * @return mixed
     */
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
