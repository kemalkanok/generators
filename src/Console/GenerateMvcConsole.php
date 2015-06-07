<?php namespace Kanok\Generators\Console;

use Kanok\Generators\Command\GenerateControllerCommand;
use Kanok\Generators\Command\GenerateCrudCommand;
use Kanok\Generators\Command\GenerateModelCommand;
use Illuminate\Console\Command;
use Kanok\Generators\Command\GenerateRequestCommand;
use Symfony\Component\Console\Input\InputOption;

class GenerateMvcConsole extends Command {

	/**
	 * The console command name.
	 *
	 * @var string
	 */
	protected $name = 'generate:mvc';

	/**
	 * The console command description.
	 *
	 * @var string
	 */
	protected $description = 'Generates A Whole package of Model , View , Controller , Request , etc..';


    /**
     * @var General
     */
    private $conf;


    /**
     * Create a new command instance.
     */

	public function __construct( )
	{
		parent::__construct();
    }

	/**
	 * Execute the console command.
	 *
	 * @return mixed
	 */
	public function fire()
	{
        $this->generalPrepare();
        $this->callCommands();

        $this->info('All Set is completed!');
    }



    /**
     * Gets minimal required info from user
     *
     */
    function generalPrepare()
    {
        $tableName = $this->ask('Table Name:');
        $modelName = $this->ask('Model Name:');
        $i = 1;
        $fields = [];
        while (true) {
            $field = trim($this->ask($i . '.Column Specs:'));
            if ($field == "") {
                break;
            }
            $field = explode(':', $field);
            $fields[$field[0]] = array_splice($field, 1);
            $i++;
        }
        $this->conf = (object)compact('tableName','modelName','fields');
    }

    /*









    function updateRoutes()
    {
        //get the model stub
        $routesPath = 'Http\Routes.php';
        $modelStub = $this->general->getFile($routesPath);
        $modelStub .= "
        resource('".$this->modelName."','".$this->modelName."');";
        $this->general->writeAppFile($routesPath,$modelStub);

        $this->info('routes updated!');
    }*/

    /**
	 * Get the console command arguments.
	 *
	 * @return array
	 */
	protected function getArguments()
	{
		return [
		];
	}

	/**
	 * Get the console command options.
	 *
	 * @return array
	 */
	protected function getOptions()
	{
		return [
			['auth', null, InputOption::VALUE_NONE, 'Creates Package for Authentication.', null],
			['gallery', null, InputOption::VALUE_NONE, 'Creates Package for Gallery', null],
		];
	}

    private function callCommands($model = 'Default')
    {
        (new GenerateModelCommand($model,$this->conf))->fire();
        (new GenerateRequestCommand($model,$this->conf))->fire();
        (new GenerateControllerCommand($model,$this->conf))->fire();
        (new GenerateCrudCommand($model,$this->conf))->fire();
    }

}
