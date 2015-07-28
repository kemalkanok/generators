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
use Kanok\Generators\Libs\NameHelper;

class GenerateMigrationCommand extends Command implements SelfHandling {
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
     * Generate migration
     * @return bool
     */
    function generateModel()
    {
        //get the model stub
        $modelStubPath  = 'Stubs/Migration/Default.stub';
        $modelStub      = $this->general->getFile($modelStubPath);

        //bind the model
        $migrationup    = "";

        foreach($this->conf->fields as $k => $fieldSet)
        {
            $migrationup.='$table';
            foreach($fieldSet as $key => $value)
            {
                $migrationup.='->';
                if($key == 0)
                {
                    $migrationup.=$value.'(\''.$k.'\')';
                }
                else
                {
                    $migrationup.=$value.'()';
                }
            }
            $migrationup .=";
            ";
        }

        $modelStub = $this->general->quickStubDataBinding($modelStub, [
            'class'         => $this->name->getMigrationClassName($this->conf->tableName),
            'table'         => $this->conf->tableName,
            'migration_up'  => $migrationup,
        ]);
        // write the file
        $modelPath = '../database/migrations/'.date('Y_m_d_His').'_'.$this->name->getMigrationName($this->conf->tableName) .'.php';
        $this->general->writeAppFile($modelPath, $modelStub);
        //give a nice good news screen

        return true;
    }
}