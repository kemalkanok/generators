<?php

namespace Kanok\Generators\Job;

use App\Jobs\Job;
use Illuminate\Contracts\Bus\SelfHandling;
use Kanok\Generators\Libs\FileHandler;

class CreateMigration extends Job implements SelfHandling
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
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $content = $this->bindFields();
        $filename = $this->prepareFile();
        $options = (object)compact('content','filename');
        $this->writeOutput($options);
    }

    private function writeOutput($options)
    {
        $this->fileHandler->writeFileToApp($options->content,'../database/migrations'.$options->filename);
    }

    private function prepareFile()
    {
        return date('Y_m_d_His').'_create_'.$this->data->tableName.'_table.php';
    }

    /**
     * Binds the stub for migration
     * 
     * @return string
     */
    private function preapareStub($fields)
    {
        $content = $this->fileHandler->readFileFromBundle('Migration/Create.stub');
        $content = str_replace('{fields}',$fields,$content);
        $content = str_replace('{tablename}',$this->data->tableName,$content);
        $UTableName = explode("_", $this->data->tableName);
        foreach($UTableName as $key => $name)
        {
            $UTableName[$key] = ucwords($name);
        }
        $UTableName = implode("", $UTableName);
        return str_replace('{utablename}',$UTableName,$content);
    }


    /**
     * Creates a string for laravel binding
     * 
     * @return string
     */
    private function bindFields()
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
        $fields = substr($fields, 0,-13);
        return $this->preapareStub($fields);
    }
}
