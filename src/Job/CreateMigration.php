<?php

namespace Kanok\Generators\Job;

use Illuminate\Contracts\Bus\SelfHandling;
use Kanok\Generators\Contracts\CreateJobContract;
use Kanok\Generators\Job\Core\GenerateJob;
use Kanok\Generators\Libs\FileHandler;

class CreateMigration extends GenerateJob implements SelfHandling, CreateJobContract
{

	/**
	 * General data object for transfering inherited data
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
	 * Create a new job instance.
	 *
	 * @param $data
	 * @internal param FileHandler $fileHandler
	 */
	public function __construct($data)
	{
		$this->data = $data;
		$this->fileHandler = new FileHandler;
	}

	/**
	 *  generates a config set for writing
	 * 
	 *  @return object
	 */ 
	function prepareOptionsForWriting()
	{
		$content = $this->bindFields();
		$filename = $this->prepareFile();
		return (object)compact('content','filename');

	}

	/**
	 * Writes out the migration
	 * 
	 * @return void
	 */
	public  function writeOutput($options)
	{
		$path = 'database/migrations/';
		$this->fileHandler->writeFileToApp($options->content,$path,$options->filename);
	}

	/**
	 * returns the proper filename
	 * 
	 * @return string 
	 */
	public  function prepareFile()
	{
		return date('Y_m_d_His').'_create_'.$this->data->tableName.'_table.php';
	}

	/**
	 * Binds the stub for migration
	 * 
	 * @return string
	 */
	public  function prepareStub($fields)
	{
		$content = $this->fileHandler->readFileFromBundle('Migration/Create.stub');
		$content = str_replace('{fields}',$fields,$content);
		$content = str_replace('{tablename}',$this->data->tableName,$content);
        $UTableName = $this->makeTableNameCapitalized();
		return str_replace('{utablename}',$UTableName,$content);
	}


	/**
	 * Creates a string for laravel migration binding
	 * 
	 * @return string
	 */
	public  function bindFields()
	{
		$fields = "";
		foreach ($this->data->fields as $fieldName => $field) {
			$fields .= '$table';
			foreach ($field as $key => $property) {
				if ($key == 1) {
					$fields .= '->' . $property . '(\'' . $fieldName . '\')';
				} else {
					$fields .= '->' . $property . '()';
				}
			}
			$fields .= ';
			';
		}
		$fields = substr($fields, 0, strripos($fields,';') + 1);
		return $this->prepareStub($fields);
	}

    /**
     * Returns a capitalized table name
     *
     * @return string
     */
    private function makeTableNameCapitalized()
    {
        $UTableName = explode("_", $this->data->tableName);
        foreach ($UTableName as $key => $name) {
            $UTableName[$key] = ucwords($name);
        }
        $UTableName = implode("", $UTableName);
        return $UTableName;
    }

}