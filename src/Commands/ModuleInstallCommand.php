<?php
namespace Houdunwang\Module\Commands;

use Houdunwang\Module\Models\ModuleMenu;
use Houdunwang\Module\Models\ModuleMenuGroup;
use Houdunwang\Module\Models\Module;
use Houdunwang\Module\Traits\BaseTrait;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;
use Spatie\Permission\Models\Permission;

class ModuleInstallCommand extends Command
{
    use BaseTrait;
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'hd:module-install {name}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'create a config';

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
        if ($this->checkModuleExists($this->module) === true) {
            //安装菜单
            $this->call('hd:menu-install', [
                'name' => $this->module,
            ]);
            //生成权限
            $this->call('hd:permission', [
                'name' => $this->module,
            ]);

            $config          = include $this->getModuleConfigPath($this->module).'/config.php';
            $instance        = new Module();
            $instance->title = $config['name'];
            $instance->name  = $this->module;
            $instance->save();
            $this->info("module {$this->module} install successful");
        }
    }
}
