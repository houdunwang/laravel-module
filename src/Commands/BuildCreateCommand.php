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
        $this->call('hd:handle', ['model' => $this->model, 'module' => $this->module]);
        $this->createController();
        $this->createRequest();
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

        $content = str_replace('{REQUEST_RULE}', var_export($this->getRequestRule(),true), $content);
        $content = str_replace('{REQUEST_RULE_MESSAGE}', var_export($this->getRequestRuleMessage(),true), $content);

        $file    = $this->getVar('REQUEST_PATH').$this->model.'Request.php';
        file_put_contents($file, $content);
        $this->info('request create successflly');
    }

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
