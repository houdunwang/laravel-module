<?php
namespace Houdunwang\Module\Commands;

use Illuminate\Console\Command;
use Artisan;
use Storage;

class ModuleCreateCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'hd:module {name}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'create a module';

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
        $name = ucfirst($this->argument('name'));
        if (\Module::has($name)) {
            return $this->error("Module [{$this->name}] already exists");
        }
        $this->call('module:make', [
            'name' => [$name],
        ]);
        $this->call('hd:config', [
            'name' => $name,
        ]);

        //创建前端文件
        $jsPath = \Module::getPath('Module')."/{$name}/Resources/assets/js";
        copy(__DIR__.'/../../resources/js/bootstrap.js',$jsPath.'/bootstrap.js');
        copy(__DIR__.'/../../resources/js/app.js',$jsPath.'/app.js');
        copy(__DIR__.'/../../resources/js/ExampleComponent.vue',$jsPath.'/ExampleComponent.vue');
        copy(__DIR__.'/../copys/package.json',\Module::getPath('Module')."/{$name}/package.json");
        copy(__DIR__.'/../copys/gitignore',\Module::getPath('Module')."/{$name}/.gitignore");
    }
}
