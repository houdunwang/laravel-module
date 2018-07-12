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
    protected $signature = 'hd:build {model} {module} {model_title} {module_title}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'create  controller view request';
    protected $module;
    protected $model;
    protected $modelInstance;
    protected $modelTitle;
    protected $moduleTitle;

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
        $this->modelTitle = ucfirst($this->argument('model_title'));
        $this->moduleTitle = ucfirst($this->argument('module_title'));
        $this->setVars($this->model, $this->module);
        if ($this->check()) {
            $this->setModelInstance();
            $this->setVar('MODEL_TITLE', $this->modelTitle);
            $this->call('hd:handle', ['model' => $this->model, 'module' => $this->module]);
            $this->createController();
            $this->createRequest();
            $this->createRoute();
            $this->createViews();
            $this->setModelFillable();
            $this->setModuleMenus();
        }
    }

    protected function setModuleMenus()
    {
        $file =$this->getVar('MODULE_PATH').'config/menus.php';
        $menus = include $file;
        if ( ! isset($menus[$this->getVar('SMODULE')])) {
            $menus[$this->getVar('SMODULE')] = [
                "title"      => "{$this->moduleTitle}管理",
                "icon"       => "fa fa-navicon",
                'permission' => '权限标识',
                "menus"      => []
            ];
        }
        $menus[$this->getVar('SMODULE')]['menus'][]=
            ["title" => "{$this->modelTitle}管理", "permission" => '', "url" => "/{$this->vars['SMODULE']}/{$this->vars['SMODEL']}"]
        ;
        file_put_contents($file,'<?php return '.var_export($menus,true).';');
    }

    protected function setModelFillable()
    {
        $columns = array_keys($this->getColumnData($this->modelInstance));
        $columns = "['".implode("','", $columns)."'];";
        $model   = $this->vars['MODEL_PATH'].$this->model.'.php';
        $content = file_get_contents($model);
        $content = preg_replace('@(protected\s+\$fillable\s*=\s*)(.*?);@', '${1}'.$columns, $content);
        file_put_contents($model, $content);
    }

    protected function check(): bool
    {
        $model = $this->vars['NAMESPACE'].'Entities\\'.$this->model;
        if ( ! class_exists($model)) {
            $this->error("module {$model} no exists");

            return false;
        }

        return true;
    }

    protected function createViews()
    {
        foreach (['create', 'edit', 'index', 'show'] as $name) {
            $content = $this->replaceVars(__DIR__."/build/views/{$name}.blade.php");
            $dir     = $this->vars['MODULE_PATH']."Resources/views/{$this->vars['SMODEL']}/";
            is_dir($dir) or mkdir($dir, 0755, true);
            Storage::makeDirectory($dir);
            $file = $dir."{$name}.blade.php";
            if ( ! is_file($file)) {
                file_put_contents($file, $content);
            }
        }
        $this->info('view create successflly');
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
        $file = $this->getVar('CONTROLLER_PATH').$this->model.'Controller.php';
        if (is_file($file)) {
            $this->info('controller file is exists');

            return false;
        }
        $content = $this->replaceVars(__DIR__.'/build/controller.tpl');
        file_put_contents($file, $content);
        $this->info('controller create successflly');
    }

    public function createRequest()
    {
        $file = $this->getVar('REQUEST_PATH').$this->model.'Request.php';
        if (is_file($file)) {
            $this->info('request file is exists');

            return false;
        }
        $content = $this->replaceVars(__DIR__.'/build/request.tpl');
        $content = str_replace('{REQUEST_RULE}', var_export($this->getRequestRule(), true), $content);
        $content = str_replace('{REQUEST_RULE_MESSAGE}', var_export($this->getRequestRuleMessage(), true), $content);
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
