<?php
/** .-------------------------------------------------------------------
 * |      Site: www.hdcms.com
 * |      Date: 2018/6/25 下午3:13
 * |    Author: 向军大叔 <2300071698@qq.com>
 * '-------------------------------------------------------------------*/

namespace Houdunwang\Module;

use Illuminate\Support\Facades\Facade as LaravelFacade;

/**
 * Class Facade
 *
 * @package Houdunwang\Module
 */
class Facade extends LaravelFacade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return 'HdModule';
    }
}
