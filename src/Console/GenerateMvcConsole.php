<?php namespace Kanok\Generators\Console;


use Illuminate\Console\Command;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Kanok\Generators\Job\GeneratePackage;
use Kanok\Generators\Libs\Config;

class GenerateMvcConsole extends Command {
    use DispatchesJobs;
	/**
	 * The console command name.
	 *
	 * @var string
	 */
	protected $name = 'gen:pack';

	/**
	 * The console command description.
	 *
	 * @var string
	 */
	protected $description = 'Generates A Whole package of Model , View , Controller , Command , Request , etc..';

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
        $data = $this->getInput();
        $this->dispatch(new GeneratePackage($data));
    }

    /**
     * Get input data from user
     *
     * @return object
     */
    private function getInput()
    {
        $modelName = $this->ask('Please Enter the model name');

        $state = $this->askWithCompletion('What would you like to do?['.join('/',$this->getStates()).']',$this->getStates());

        $fields = $this->getFields();

        $type = $this->askWithCompletion('What would you like to do?['.join('/',$this->getTypes()).']',$this->getTypes());

        return (object)compact('modelName', 'fields','state','type');
    }

    /**
     * Get state info from config
     *
     * @return mixed
     */
    private function getStates()
    {
        return (new Config())->get('status');
    }

    /**
     * Get types from config
     *
     * @return array
     * @internal param $types
     */
    private function getTypes()
    {
        $data = (new Config())->get('packages');
        foreach ($data as $key => $cols) {
            $types[] = $key;
        }
        return  $types;
    }

    /**
     * Get fields from user
     *
     * @return array
     */
    private function getFields()
    {
        $this->info('Please enter the specific fields for creating or altering table, To stop enter "stop"');
        $i = 1;
        $fields = [];
        while (true) {
            $field = $this->ask($i . '.Field Specs');
            //stop if data is stop
            if (trim($field) == "stop") {
                break;
            }
            $field = explode(':', $field);
            array_slice($field, 2);
            $key = $field[0];
            unset($field[0]);
            $fields[$key] = $field;
            $i++;
        }
        return $fields;
    }

}