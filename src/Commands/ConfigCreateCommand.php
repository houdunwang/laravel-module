<?php
namespace Houdunwang\Module\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;

class ConfigCreateCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'hd:config {name}';

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

        if ($this->checkModuleExists() === true) {
            $this->copyFiles();
        }
    }

    protected function getModuleConfigPath()
    {
        return config('modules.paths.modules')."/{$this->module }/Config/";
    }

    protected function checkModuleExists()
    {

        if (is_dir($this->getModuleConfigPath())) {
            return true;
        }
        $this->error('Module does not exist');
    }

    protected function copyFiles()
    {
        $files = glob(__DIR__.'/../config/*.php');
        foreach ($files as $file) {
            $to = $this->getModuleConfigPath().basename($file);
            if ( ! is_file($to)) {
                copy($file, $to);
            }
        }
        $this->info('file create Successful');
    }
}
