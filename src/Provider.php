<?php
/** .-------------------------------------------------------------------
 * |      Site: www.hdcms.com
 * |      Date: 2018/6/25 下午3:13
 * |    Author: 向军大叔 <2300071698@qq.com>
 * '-------------------------------------------------------------------*/

namespace Houdunwang\Module;

use Houdunwang\Module\Traits\ConfigService;
use Houdunwang\Module\Traits\MenusService;
use Houdunwang\Module\Traits\PermissionService;

/**
 * Class Facade
 *
 * @package Houdunwang\Module
 */
class Provider
{
    use ConfigService, PermissionService, MenusService;

    /**
     * 当前模块
     * @return mixed
     */
    public function currentModule()
    {
        $controller = \Route::getCurrentRoute()->getAction()['controller'];
        preg_match('@\\\(.*?)\\\@i', $controller, $match);

        return $module = ($match[1]);
    }
}
