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
    protected $signature = 'hd:permission {name}';

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
        $this->module = ucfirst($this->argument('name'));

        $permissions = include $this->getModuleConfigPath().'permission.php';
        app()['cache']->forget('spatie.permission.cache');
        $this->resetTables();
        foreach ($permissions as $accessLists) {
            foreach ($accessLists as $access) {
                $name = $this->module.'::'.$access;
                Permission::create(['name' => $name]);
            }
        }
        $this->info("{$this->module} permission install successFully");
    }

    protected function resetTables()
    {
        $ids = \DB::table('permissions')->where('name', 'like', "{$this->module}::%")->pluck('id');
        if ($ids) {
            \DB::table('model_has_permissions')->whereIn('permission_id', $ids)->delete();
            \DB::table('role_has_permissions')->whereIn('permission_id', $ids)->delete();
            \DB::table('permissions')->whereIn('id', $ids)->delete();
        }
    }

    protected function getModuleConfigPath()
    {
        return config('modules.paths.modules')."/{$this->module }/Config/";
    }
}
