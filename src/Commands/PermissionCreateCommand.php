<?php
namespace Houdunwang\Module\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;
use Spatie\Permission\Models\Permission;

class PermissionCreateCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'hd:permission {name=0}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = '生成权限数据';

    protected $module;

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        app()['cache']->forget('spatie.permission.cache');
        foreach ($this->getModules() as $module) {
            $config = \HDModule::config('admin.permission');
            foreach ($config as $group) {
                foreach ($group['permissions'] as $permission) {
                    if ( ! Permission::where(['name' => $permission['name'], 'guard_name' => $permission['guard']])->first()) {
                        Permission::create(['name'=>$permission['name'],'guard_name'=>$permission['guard']]);
                    }
                }
            }
            $this->info("{$module} permission install successFully");
        }
    }

    protected function getModules()
    {
        $name    = ucfirst($this->argument('name'));
        $modules = [];
        if ($name) {
            $modules[] = $name;
        } else {
            $modules = array_keys(\Module::getOrdered());
        }

        return $modules;
    }
}
