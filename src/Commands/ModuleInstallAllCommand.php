<?php
namespace Houdunwang\Module\Commands;

use Houdunwang\Module\Models\ModuleMenu;
use Houdunwang\Module\Models\ModuleMenuGroup;
use Houdunwang\Module\Models\Module;
use Houdunwang\Module\Traits\BaseTrait;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;
use Spatie\Permission\Models\Permission;

class ModuleInstallAllCommand extends Command
{
    use BaseTrait;
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'hd:module-install-all';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'install all module';

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
        $info    = "No module installation";
        $modules = [];
        foreach (glob(config('modules.paths.modules').'/*') as $dir) {
            $module = ucfirst(basename($dir));
            if ($this->checkModuleExists($module)) {
                $modules[] = $module;
                $this->call('hd:module-install', ['name' => $module]);
            }
        }
        if (empty($modules)) {
            $this->error($info);
        }else{
            $this->info("all module install successful");
        }
        return true;
    }
}
