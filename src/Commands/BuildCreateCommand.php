<?php
namespace Houdunwang\Module\Commands;

use Houdunwang\LaravelView\Traits\Db;
use Houdunwang\Module\Traits\BuildVars;
use Illuminate\Console\Command;
use Artisan;
use Storage;

class BuildCreateCommand extends Command
{
    use BuildVars, Db;
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'hd:build {model} {module}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'create  controller view request';
    protected $module;
    protected $model;
    protected $modelInstance;

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    public function handle()
    {
        $this->model  = ucfirst($this->argument('model'));
        $this->module = ucfirst($this->argument('module'));
        $this->setVars($this->model, $this->module);
        $this->setModelInstance();
        //$this->setVar('TABLE_COMMENT', $this->getTableComment($this->modelInstance));
//die;

        $this->call('hd:handle', ['model' => $this->model, 'module' => $this->module]);
        if ($this->isCreated()) {
            //return $this->info('buile file is exists');
        }
        $this->createController();
        $this->createRequest();
        $this->createRoute();
        $this->createViews();
    }

    protected function createViews()
    {
        foreach (['create', 'edit', 'index', 'show'] as $name) {
            $content = $this->replaceVars(__DIR__."/build/views/{$name}.blade.php");
            $dir = $this->vars['MODULE_PATH']."Resources/views/{$this->vars['SMODEL']}/";
            is_dir($dir) or mkdir($dir,0755,true);
            Storage::makeDirectory($dir);
            file_put_contents($dir."{$name}.blade.php",$content);
        }
    }

    protected function isCreated(): bool
    {
        $file = $this->getVar('CONTROLLER_PATH').$this->model.'Controller.php';

        return is_file($file);

    }

    protected function createRoute()
    {
        $file  = $this->getVar('MODULE_PATH').'/Http/routes.php';
        $route = file_get_contents($file);
        //检测路由
        if (strstr($route, "{$this->vars['SMODULE']}-{$this->vars['SMODEL']}-route")) {
            return $this->info('route is exists');
        }
        $route .= <<<str
\n 
//{$this->vars['SMODULE']}-{$this->vars['SMODEL']}-route
Route::group(['middleware' => ['web', 'auth:admin'],'prefix'=>'{$this->vars['SMODULE']}','namespace'=>"Modules\\{$this->vars['MODULE']}\Http\Controllers"], 
function () {
    Route::resource('{$this->vars['SMODEL']}', '{$this->vars['MODEL']}Controller')->middleware("permission:admin,resource");
});
str;
        file_put_contents($file, $route);
        $this->info('route create successfully');
    }

    protected function setModelInstance()
    {
        $class               = $this->vars['NAMESPACE'].'Entities\\'.$this->model;
        $this->modelInstance = new $class;
    }

    public function createController()
    {
        $content = $this->replaceVars(__DIR__.'/build/controller.tpl');
        $file    = $this->getVar('CONTROLLER_PATH').$this->model.'Controller.php';
        file_put_contents($file, $content);
        $this->info('controller create successflly');
    }

    public function createRequest()
    {
        $content = $this->replaceVars(__DIR__.'/build/request.tpl');
        $content = str_replace('{REQUEST_RULE}', var_export($this->getRequestRule(), true), $content);
        $content = str_replace('{REQUEST_RULE_MESSAGE}', var_export($this->getRequestRuleMessage(), true), $content);

        $file = $this->getVar('REQUEST_PATH').$this->model.'Request.php';
        file_put_contents($file, $content);
        $this->info('request create successflly');
    }

    /**
     * 设置验证规则
     *
     * @return array
     */
    protected function getRequestRule()
    {
        $class   = $this->vars['NAMESPACE'].'Entities\\'.$this->model;
        $model   = new $class;
        $columns = $this->formatColumns($model);
        $rules   = [];
        foreach ($columns as $column) {
            $check = $column && in_array($column['name'], $model->getFillAble());
            if ($check && $column['nonull']) {
                $rules[$column['name']] = 'required';
            }
        }

        return $rules;
    }

    /**
     * 验证提示信息
     *
     * @return array
     */
    protected function getRequestRuleMessage()
    {
        $class   = $this->vars['NAMESPACE'].'Entities\\'.$this->model;
        $model   = new $class;
        $columns = $this->formatColumns($model);
        $rules   = [];
        foreach ($columns as $column) {
            $check = $column && in_array($column['name'], $model->getFillAble());
            if ($check && $column['nonull']) {
                $rules[$column['name'].'.required'] = $column['title'].'不能为空';
            }
        }

        return $rules;
    }
}
