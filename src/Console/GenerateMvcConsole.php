<?php namespace Kanok\Generators\Console;

use Kanok\Generators\Command\GenerateControllerCommand;
use Kanok\Generators\Command\GenerateCrudCommand;
use Kanok\Generators\Command\GenerateMigrationCommand;
use Kanok\Generators\Command\GenerateModelCommand;
use Illuminate\Console\Command;
use Kanok\Generators\Command\GenerateRequestCommand;
use Kanok\Generators\Command\UpdateRoutesCommand;
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
        if($this->option('api'))
        {
            $this->callCommands('api');

        }
        else
        {
            $this->callCommands();
        }

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
			['api', 'a', InputOption::VALUE_NONE, 'creates crud package for api integration', null],
		];
	}

    private function callCommands($model = 'Default')
    {
        (new GenerateMigrationCommand($model,$this->conf))->fire();
        (new GenerateModelCommand($model,$this->conf))->fire();
        (new GenerateRequestCommand($model,$this->conf))->fire();
        (new GenerateControllerCommand($model,$this->conf))->fire();
        (new GenerateCrudCommand($model,$this->conf))->fire();
        (new UpdateRoutesCommand($model,$this->conf))->fire();
    }

}
