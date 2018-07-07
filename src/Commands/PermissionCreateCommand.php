<?php
namespace Houdunwang\Module\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;
use Spatie\Permission\Models\Permission;

/**
 * Class PermissionCreateCommand
 *
 * @package Houdunwang\Module\Commands
 */
class PermissionCreateCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'hd:permission {name?}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = '生成权限数据';

    /**
     * @var
     */
    protected $module;

    public function handle()
    {
        app()['cache']->forget('spatie.permission.cache');
        foreach ((array)$this->getModules() as $module) {
            $config = \HDModule::config($module.'.permission');
            foreach ((array)$config as $group) {
                foreach ((array)$group['permissions'] as $permission) {
                    if ( ! $this->permissionIsExists($permission)) {
                        Permission::create(['name' => $permission['name'], 'guard_name' => $permission['guard']]);
                    }
                }
            }
            $this->info("{$module} permission install successFully");
        }
    }

    /**
     * 检查权限标识
     *
     * @param array $permission
     *
     * @return bool
     */
    protected function permissionIsExists(array $permission): bool
    {
        $where = [
            ['name', '=', $permission['name']],
            ['guard_name', '=', $permission['guard']],
        ];

        return (bool)Permission::where($where)->first();
    }

    /**
     * 获取模块
     *
     * @return array
     */
    protected function getModules(): array
    {
        $modules = [];
        if ($module = $this->argument('name')) {
            $modules[] = ucfirst($module);
        } else {
            $modules = array_keys(\Module::getOrdered());
        }

        return $modules;
    }
}
