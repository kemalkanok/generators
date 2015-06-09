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
     * @var array
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
        if($this->option('auth_api'))
        {
           //auth api state
            $this->prepareWithoutInput('auth_api');

            //$this->callCommands('auth_api');
            $this->conf->modelName = 'Register';
            (new GenerateRequestCommand('Default',$this->conf))->fire();

        }
        else
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
	 * Get the console command options.
	 *
	 * @return array
	 */
	protected function getOptions()
	{
		return [
			['api', 'a', InputOption::VALUE_NONE, 'creates crud package for api integration', null],
			['auth_api', null, InputOption::VALUE_NONE, 'creates crud package for api integration', null],
		];
	}

    /**
     * Batch Command Caller
     * @param string $model
     */
    private function callCommands($model = 'Default')
    {
        if((new GenerateMigrationCommand($model,$this->conf))->fire())
        {
            $this->info('Migration Created');
        }
        if((new GenerateModelCommand($model,$this->conf))->fire())
        {
            $this->info('Model Created');
        }
        if((new GenerateRequestCommand($model,$this->conf))->fire())
        {
            $this->info('Request Created');
        }
        if((new GenerateControllerCommand($model,$this->conf))->fire())
        {
            $this->info('Controller Created');
        }
        if($model != 'auth_api')
        {
            if((new GenerateCrudCommand($model,$this->conf))->fire())
            {
                $this->info('Crud Created');
            }
        }

        if((new UpdateRoutesCommand($model,$this->conf))->fire())
        {
            $this->info('Route Updated');
        }
    }

    /**
     * General Prepare method for no input reasons
     * @param $key
     */
    private function prepareWithoutInput($key)
    {
        $this->conf = new \StdClass();

        switch($key)
        {
            case 'auth_api':
                $this->conf->tableName = "users";
                $this->conf->modelName = "User";
                $this->conf->fields = [
                    'username' => [
                        'string'
                    ],
                    'password' => [
                        'string'
                    ],
                    'email' => [
                        'string'
                    ],
                ];
            break;
        }

    }

}
