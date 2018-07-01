<?php
/** .-------------------------------------------------------------------
 * |      Site: www.hdcms.com
 * |      Date: 2018/6/25 下午2:54
 * |    Author: 向军大叔 <2300071698@qq.com>
 * '-------------------------------------------------------------------*/

namespace Houdunwang\Module;

use Houdunwang\Module\Commands\PermissionCreateCommand;
use Illuminate\Support\ServiceProvider;
use Houdunwang\Module\Commands\ModuleCreateCommand;
use Houdunwang\Module\Commands\ConfigCreateCommand;

class LaravelServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        if ($this->app->runningInConsole()) {
            $this->commands([
                ModuleCreateCommand::class,
                ConfigCreateCommand::class,
                PermissionCreateCommand::class
            ]);
        }
    }

    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton('HdModule', function ($app) {
            return new Provider();
        });
    }
}
