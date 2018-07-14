<?php
namespace Houdunwang\Module\Commands;

use Houdunwang\Module\Traits\BuildVars;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;

class ConfigCreateCommand extends Command
{
    use BuildVars;
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
        $this->setVars('',$this->module);
        if (\Module::has($this->module)) {
            $this->copyFiles();
        }
    }

    protected function copyFiles()
    {
        $files = glob(__DIR__.'/../config/*.php');
        foreach ($files as $file) {
            $to = \Module::getModulePath($this->module).'Config/'.basename($file);
            if (is_file($to)) {
                $this->info($to." is exists");
                continue;
            }
            file_put_contents($to,$this->replaceVars($file));
            $this->info("{$to} file create successfully");
        }
    }
}
