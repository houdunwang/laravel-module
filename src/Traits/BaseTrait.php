<?php
/** .-------------------------------------------------------------------
 * |      Site: www.hdcms.com  www.houdunren.com
 * |      Date: 2018/7/2 下午5:26
 * |    Author: 向军大叔 <2300071698@qq.com>
 * '-------------------------------------------------------------------*/
namespace Houdunwang\Module\Traits;
use Houdunwang\Module\Models\Module;

trait BaseTrait
{
    protected function getModuleConfigPath($module)
    {
        return config('modules.paths.modules')."/{$module}/Config/";
    }

    protected function checkModuleExists($module): bool
    {
        $status = is_dir($this->getModuleConfigPath($module));
        if ($status) {
            return true;
        }
        $this->error('Module does not exist');

        return false;
    }
}
