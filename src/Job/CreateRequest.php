<?php

namespace Kanok\Generators\Job;

use Illuminate\Contracts\Bus\SelfHandling;
use Kanok\Generators\Contracts\CreateJobContract;
use Kanok\Generators\Job\Core\GenerateJob;
use Kanok\Generators\Libs\FileHandler;

class CreateRequest extends GenerateJob implements SelfHandling, CreateJobContract
{

	/**
	 * General data object for transferring inherited data
	 * 
	 * @var Object
	 */
	private $data;
	/**
	 * General file operations
	 * 
	 * @var FileHandler
	 */
	private $fileHandler;

    /**
     * Capitaliazed table name
     * @var
     */
    private $UTablename;

    /**
	 * Create a new job instance.
	 *
	 * @param $data
	 * @internal param FileHandler $fileHandler
	 */
	public function __construct($data)
	{
		$this->data = $data;
		$this->makeTableName();
		$this->fileHandler = new FileHandler;
	}

	/**
	 * Determines the table name
	 * 
	 *	@return void
	 */  
	private function makeTableName()
	{
		$data = preg_split('/(?=[A-Z])/',$this->data->modelName);
		unset($data[0]);
		if(count($data) > 1)
		{
			$this->data->tableName = str_plural(strtolower(implode('_',$data)));	
		}
		else if(count($data)  == 1)
		{
			$this->data->tableName = str_plural(strtolower($data[1]));
		}

	}

    /**
     * Writes out the migration
     *
     * @param $options object
     */
	public  function writeOutput($options)
	{
		$path = 'app/Http/Requests/';
		$this->fileHandler->writeFileToApp($options->content,$path,$options->filename);
	}

	/**
	 * returns the proper filename
	 * 
	 * @return string 
	 */
	public  function prepareFile()
	{
		return $this->data->modelName.'Request.php';
	}

	/**
	 * Binds the stub for migration
	 * 
	 * @return string
	 */
	public function prepareStub($fields)
	{
		$content = $this->fileHandler->readFileFromBundle('Request/'.$this->data->type.'.stub');
		$content = str_replace('{fields}',$fields[0],$content);
		$content = str_replace('{ufields}',$fields[1],$content);
		$content = str_replace('{tablename}',$this->data->tableName,$content);
		return str_replace('{utablename}',$this->data->modelName,$content);
	}


	/**
	 * Creates a string for laravel migration binding
	 * 
	 * @return string
	 */
	public  function bindFields()
	{
		$fields = "";
		$ufields = "";
		foreach ($this->data->fields as $fieldName => $field) {
			if(in_array('unique', $field))
			{
				$fields .=  '\''.$fieldName. '\' => \'Required|unique:'.$this->data->tableName.','.$fieldName.'\',
';				$ufields .=  '\''.$fieldName. '\' => \'Required|unique:'.$this->data->tableName.','.$fieldName.',\'.$user->id,
';	
			}
			else
			{
				$fields .=  '\''.$fieldName. '\' => \'Required\' ,
';				$ufields .=  '\''.$fieldName. '\' => \'Required\' ,
';
			}
		}
		$fields = substr($fields,0,strripos($fields, ','));
		$ufields = substr($ufields,0,strripos($ufields, ','));
		return $this->prepareStub([$fields,$ufields]);
	}

    

}