<?php namespace Kanok\Generators\Console;

use Kanok\Generators\Libs\General;
use Illuminate\Console\Command;
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



    protected $tableName = "";
    protected $modelName = "";
    protected $fields = [];
    /**
     * @var General
     */
    private $general;


    /**
     * Create a new command instance.
     */

	public function __construct( )
	{
		parent::__construct();
        $this->general = app('Kanok\Generators\Libs\General');
    }

	/**
	 * Execute the console command.
	 *
	 * @return mixed
	 */
	public function fire()
	{
        $this->generalPrepare();
        $this->generateModel();
        $this->generateRequest();
        $this->generateController();
        $this->generateIndex();
        $this->generateCreate();
        $this->generateEdit();
        $this->updateRoutes();


    }

    /**
     * Gets minimal required info from user
     *
     */
    function generalPrepare()
    {
        $this->tableName = $this->ask('Table Name:');
        $this->modelName = $this->ask('Model Name:');
        $i = 1;
        while (true) {
            $field = trim($this->ask($i . '.Column Specs:'));
            if ($field == "") {
                break;
            }
            $field = explode(':', $field);
            $this->fields[$field[0]] = array_splice($field, 1);
            $i++;
        }
    }

    function generateModel()
    {
        //get the model stub
        $modelStubPath = 'Stubs/Model/Default.stub';
        $modelStub = $this->general->getFile($modelStubPath);
        //bind the model
        $fillables = "";
        foreach ($this->fields as $key => $value) {
            $fillables .= "'" . $key . "',";
        }
        $fillables = substr($fillables, 0, -1);

        $modelStub = $this->general->quickStubDataBinding($modelStub, [
            'model' => $this->modelName,
            'table' => $this->tableName,
            'fillable' => $fillables
        ]);
        // write the file
        $modelPath = $this->modelName . '.php';
        $this->general->writeAppFile($modelPath, $modelStub);
        //give a nice good news screen
        $this->info('model is created !');
    }


    function generateRequest()
    {
        //get the model stub
        $modelStubPath = 'Stubs/Request/Default.stub';
        $modelStub = $this->general->getFile($modelStubPath);
        //bind the model
        $fillables = "";
        foreach ($this->fields as $key => $value) {
            $fillables .= "'" . $key . "' => 'Required',";
        }
        $fillables = substr($fillables, 0, -1);

        $modelStub = $this->general->quickStubDataBinding($modelStub, [
            'model' => $this->modelName,
            'fillable' => $fillables
        ]);
        // write the file
        $modelPath = 'Http/Requests/'. $this->modelName . '.php';
        $this->general->writeAppFile($modelPath, $modelStub);
        //give a nice good news screen
        $this->info('Request is created !');
    }

    function generateController()
    {
        //get the model stub
        $modelStubPath = 'Stubs/Controller/Default.stub';
        $modelStub = $this->general->getFile($modelStubPath);
        //bind the model


        $modelStub = $this->general->quickStubDataBinding($modelStub, [
            'model' => $this->modelName
        ]);
        // write the file
        $modelPath = 'Http/Controllers/'. $this->modelName . '.php';
        $this->general->writeAppFile($modelPath, $modelStub);
        //give a nice good news screen
        $this->info('Controller is created !');
    }

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
			['auth', null, InputOption::VALUE_NONE, 'Creates Package for Authentication.', null],
			['gallery', null, InputOption::VALUE_NONE, 'Creates Package for Gallery', null],
		];
	}

}
