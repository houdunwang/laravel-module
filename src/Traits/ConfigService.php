<?php
/** .-------------------------------------------------------------------
 * |      Site: www.hdcms.com  www.houdunren.com
 * |      Date: 2018/7/2 下午2:21
 * |    Author: 向军大叔 <2300071698@qq.com>
 * '-------------------------------------------------------------------*/
namespace Houdunwang\Module\Traits;

use Module;
use HDModule;
/**
 * Class ModuleConfig
 *
 * @package Houdunwang\Module\Services
 */
trait ConfigService
{
    /**
     * 支持点语法的获取配置项
     *
     * @param $name
     *
     * @return mixed
     */
    public function config($name)
    {
        $exts = explode('.', $name);
        $file = config('modules.paths.modules').'/'.ucfirst(array_shift($exts)).'/Config/'.array_shift($exts).'.php';
        if (is_file($file)) {
            $config = include $file;

            return $exts ? array_get($config, implode('.', $exts)) : $config;
        }
    }

    public function saveConfig(array $data = [], $name = 'config')
    {
        $module = HDModule::currentModule();
        $config = array_merge(HDModule::config($module.'.'.$name), $data);
        $file   = HDModule::getModulePath().'/Config/'.$name.'.php';

        return file_put_contents($file, '<?php return '.var_export($config, true).';');
    }
}
