<?php

namespace MattYeend\LaravelModelService\Commands;

use Illuminate\Foundation\Console\ModelMakeCommand;
use Illuminate\Filesystem\Filesystem;
use Symfony\Component\Console\Input\InputOption;

class ModelMakeWithServiceCommand extends ModelMakeCommand
{
    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'make:model';

    /**
     * Configure the command options.
     *
     * @return void
     */
    protected function configure()
    {
        parent::configure();

        $this->getDefinition()->addOption(
            new InputOption(
                'service',
                'S',
                InputOption::VALUE_NONE,
                'Create a new service class for the model'
            )
        );
    }

    /**
     * Execute the console command.
     *
     * @return void
     */
    public function handle()
    {
        parent::handle();

        if ($this->option('service') || $this->option('all')) {
            $this->createService();
        }
    }

    /**
     * Create a new service class for the model.
     *
     * @return void
     */
    protected function createService()
    {
        $filesystem = new Filesystem;

        $modelName = $this->getNameInput();
        $serviceName = $modelName . 'Service';
        $serviceNamespace = 'App\\Services';
        $path = app_path("Services/{$serviceName}.php");

        if (! $filesystem->exists(app_path('Services'))) {
            $filesystem->makeDirectory(app_path('Services'), 0755, true);
        }

        $stubPath = file_exists(base_path('stubs/service.stub'))
            ? base_path('stubs/service.stub')
            : __DIR__ . '/../../stubs/service.stub';

        $stub = $filesystem->get($stubPath);
        $stub = str_replace(
            ['DummyNamespace', 'DummyClass'],
            [$serviceNamespace, $serviceName],
            $stub
        );

        $filesystem->put($path, $stub);

        $this->info("Service created successfully: {$path}");
    }
}
