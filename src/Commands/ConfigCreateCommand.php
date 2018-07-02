<?php
namespace Houdunwang\Module\Commands;

use Houdunwang\Module\Traits\BaseTrait;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;

class ConfigCreateCommand extends Command
{
    use BaseTrait;
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
        if ($this->checkModuleExists($this->module) === true) {
            $this->copyFiles();
        }
    }

    protected function copyFiles()
    {
        $files = glob(__DIR__.'/../config/*.php');
        foreach ($files as $file) {
            $to = $this->getModuleConfigPath($this->module).basename($file);
            if ( is_file($to)) {
                $this->info($to." is exists");
                continue;
            }
            copy($file, $to);
            $this->info("{$to} file create successfully");
        }
    }
}
