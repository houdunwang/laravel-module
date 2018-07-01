<?php
namespace Houdunwang\Module\Commands;

use Illuminate\Console\Command;
use Artisan;

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
        $name = $this->argument('name');
        $this->call('module:make', [
            'name' => [$name]
        ]);
        $this->call('hd:config', [
            'name' => $name
        ]);
    }
}
