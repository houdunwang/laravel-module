<?php
/** .-------------------------------------------------------------------
 * |      Site: www.hdcms.com  www.houdunren.com
 * |      Date: 2018/7/2 下午2:21
 * |    Author: 向军大叔 <2300071698@qq.com>
 * '-------------------------------------------------------------------*/
namespace Houdunwang\Module\Traits;

trait ModuleService
{
    /**
     * 当前模块
     *
     * @return mixed
     */
    public function currentModule()
    {
        $controller = \Route::getCurrentRoute()->getAction()['controller'];
        preg_match('@\\\(.*?)\\\@i', $controller, $match);

        return $module = ($match[1]);
    }

    public function getModulesLists($filter = [])
    {
        $modules = [];
        foreach (\Module::getOrdered() as $module) {
            if ( ! in_array($module->name, $filter)) {
                $modules[] = [
                    'name'   => $module->name,
                    'title'  => \HDModule::config($module->name.'.config.name'),
                    'module' => $module,
                ];
            }
        }

        return $modules;
    }
}
