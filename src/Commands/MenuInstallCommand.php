<?php
namespace Houdunwang\Module\Commands;

use Houdunwang\Module\Models\ModuleMenu;
use Houdunwang\Module\Models\ModuleMenuGroup;
use Houdunwang\Module\Traits\BaseTrait;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;
use Spatie\Permission\Models\Permission;

class MenuInstallCommand extends Command
{
    use BaseTrait;
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'hd:menu-install {name}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'create module create';

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
            $this->menuInstall();
        }
    }

    protected function menuInstall(): bool
    {
        $config = include $this->getModuleConfigPath($this->module).'/menus.php';
        ModuleMenuGroup::where('module', $this->module)->delete();
        ModuleMenu::where('module', $this->module)->delete();
        foreach ($config as $group) {
            $groupModel = new ModuleMenuGroup();
            $data       = [
                'title'      => $group['title'],
                'icon'       => $group['icon'],
                'permission' => $group['permission'] ?? '',
                'module'     => $this->module,
            ];
            $groupModel->create($data);
            foreach ($group['menus'] as $menu) {
                $menuModel = new ModuleMenu();
                $menuModel->create([
                    'title'      => $menu['title'],
                    'icon'       => $menu['icon'] ?? '',
                    'permission' => $menu['permission'],
                    'module'     => $this->module,
                ]);
            }
        }
        $this->info("{$this->module} menus install successful");

        return true;
    }
}
