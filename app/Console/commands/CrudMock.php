<?php namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Filesystem\Filesystem;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputArgument;

class CrudMock extends Command {

	/**
	 * The console command name.
	 *
	 * @var string
	 */
	protected $name = 'crud:mock';

	/**
	 * The console command description.
	 *
	 * @var string
	 */
    protected $description = 'Create Mock for each Model';

    protected $filesystem;

	/**
	 * Create a new command instance.
	 *
	 * @return void
	 */
	public function __construct(Filesystem $filesys)
	{
		parent::__construct();

        $this->filesystem = $filesys;
	}

	/**
	 * Execute the console command.
	 *
	 * @return mixed
	 */
	public function fire()
	{
        $argument = $this->argument("models");
        if(strstr($argument, ",")) {
            $models = explode(",", $this->argument("models"));
        } else {
            $models = [trim($argument)];
        }

        $this->checkDirectory();
        $template = $this->filesystem->get($this->getPath("Console/Commands/template/model.crud"));
        foreach ($models as $model) {
            $model = ucwords($model);
            $content = $this->setTemplate($template, $model);
            $this->filesystem->put($this->getPath("mocks/Mock") . $model . ".php", $content);
        }
        $this->response(count($models));
	}

    protected function checkDirectory()
    {
        if (!$this->filesystem->isDirectory($this->getPath("mocks/"))) {
            $this->filesystem->makeDirectory($this->getPath("mocks/"), $mode = 0777, true, true);
        }
    }

    protected function getPath($path)
    {
        return app_path($path);
    }

    protected function setTemplate($template, $model)
    {
        $replace = str_replace("{{MockName}}", "Mock" . $model, $template);
        $replace = str_replace("{{ModelName}}", $model, $replace);
        $replace = str_replace("{{ControllerName}}", $model . "Controller", $replace);
        $replace = str_replace("{{Route}}", strtolower($model).'/', $replace);
        $replace = str_replace("{{RouteIndex}}", strtolower($model).'/', $replace);
        return str_replace("{{ViewName}}", strtolower($model), $replace);
    }

    protected function response($models)
    {
        if ($models > 1)
            $this->line("Mocks where created");
        else
            $this->line("Mock was created");
    }

	/**
	 * Get the console command arguments.
	 *
	 * @return array
	 */
	protected function getArguments()
	{
		return [
			['models', InputArgument::REQUIRED, 'set your mock models e.g. crud:mock user,company,category'],
		];
	}

	/**
	 * Get the console command options.
	 *
	 * @return array
	 */
	protected function getOptions()
	{
		return [];
	}

}
