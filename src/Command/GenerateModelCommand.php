<?php
/**
 * Created by PhpStorm.
 * User: Kemal Kanok
 * Date: 07/06/15
 * Time: 02:00
 */

namespace Kanok\Generators\Command;


use Illuminate\Console\Command;
use Illuminate\Contracts\Bus\SelfHandling;
use Kanok\Generators\Libs\FileHandler;

class GenerateModelCommand extends Command implements SelfHandling {
    /**
     * @var FileHandler
     */
    private $general;
    /**
     * @var
     */
    private $keyword;
    /**
     * @var
     */
    private $conf;

    /**
     *
     * General Constructor Method
     * @param $keyword
     * @param $conf
     */
    function __construct( $keyword , $conf)
    {
        $this->general  = app('Kanok\Generators\Libs\FileHandler');
        $this->name     = app('Kanok\Generators\Libs\NameHelper');
        $this->keyword  = $keyword;
        $this->conf     = $conf;
    }

    /**
     * Fire Method
     */
    function fire()
    {
        return $this->generateModel();
    }

    /**
     * Generate model
     * @return bool
     */
    function generateModel()
    {
        //get the model stub
        $modelStubPath  = 'Stubs/Model/Default.stub';
        $modelStub      = $this->general->getFile($modelStubPath);

        //bind the model
        $fillables      = "";
        foreach ($this->conf->fields as $key => $value) {
            $fillables .= "'" . $key . "',";
        }
        $fillables = substr($fillables, 0, -1);
        $modelStub = $this->general->quickStubDataBinding($modelStub, [
            'model'     => $this->name->getModelName($this->conf->resource),
            'table'     => $this->conf->tableName,
            'fillable'  => $fillables
        ]);
        // write the file
        $modelPath = $this->name->getModelName($this->conf->resource) . '.php';
        $this->general->writeAppFile($modelPath, $modelStub);
        //give a nice good news screen
        return true;
        //$this->info('model is created !');
    }
}