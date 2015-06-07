<?php namespace Kanok\Generators\Console;

use Kanok\Generators\Command\GenerateControllerCommand;
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




    

    function generateIndex()
    {
        //get the model stub
        $modelStubPath = 'Stubs/View/Index/Default.stub';
        $modelStub = $this->general->getFile($modelStubPath);
        //bind the model

        $modelStub = $this->general->quickStubDataBinding($modelStub, [
            'model' => $this->modelName
        ]);
        // write the file
        $indexFolder ='../resources/views/'. $this->modelName . "/";
        $this->general->createFolder($indexFolder);

        $modelPath = '../resources/views/'. $this->modelName . '/index.blade.php';
        $this->general->writeAppFile($modelPath, $modelStub);
        //give a nice good news screen
        $this->info('index view is created !');
    }

    function generateCreate()
    {
        //get the model stub
        $modelStubPath = 'Stubs/View/Create/Default.stub';
        $modelStub = $this->general->getFile($modelStubPath);
        //bind the model

        $fillables = "";
        foreach ($this->fields as $key => $value) {

                $fillables .= '<div class="form-group">
        <label for="'.$key.'">{{trans("'.$this->modelName.'.'.$key.'")}}</label>
        <input type="text" class="form-control" id="'.$key.'"  name="'.$key.'">
    </div>
    ';




        }



        $modelStub = $this->general->quickStubDataBinding($modelStub, [
            'model' => $this->modelName,
            'form' => $fillables
        ]);
        // write the file
        $indexFolder ='../resources/views/'. $this->modelName . "/";
        $this->general->createFolder($indexFolder);

        $modelPath = '../resources/views/'. $this->modelName . '/create.blade.php';
        $this->general->writeAppFile($modelPath, $modelStub);
        //give a nice good news screen
        $this->info('create view is created !');
    }

    function generateEdit()
    {
        //get the model stub
        $modelStubPath = 'Stubs/View/Edit/Default.stub';
        $modelStub = $this->general->getFile($modelStubPath);
        //bind the model

        $fillables = "";
        foreach ($this->fields as $key => $value) {

                $fillables .= '<div class="form-group">
        <label for="'.$key.'">{{trans("'.$this->modelName.'.'.$key.'")}}</label>
        <input type="text" class="form-control" id="'.$key.'"  name="'.$key.'" value="{{$element->'.$key.'}}">
    </div>
    ';




        }



        $modelStub = $this->general->quickStubDataBinding($modelStub, [
            'model' => $this->modelName,
            'form' => $fillables
        ]);
        // write the file
        $indexFolder ='../resources/views/'. $this->modelName . "/";
        $this->general->createFolder($indexFolder);

        $modelPath = '../resources/views/'. $this->modelName . '/edit.blade.php';
        $this->general->writeAppFile($modelPath, $modelStub);
        //give a nice good news screen
        $this->info('edit view is created !');
    }


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
    }

}
