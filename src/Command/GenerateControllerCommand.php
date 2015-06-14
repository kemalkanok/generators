<?php
/**
 * Created by PhpStorm.
 * User: Kemal Kanok
 * Date: 07/06/15
 * Time: 02:00
 */

namespace Kanok\Generators\Command;


use App\Commands\Command;
use Illuminate\Contracts\Bus\SelfHandling;
use Kanok\Generators\Libs\FileHandler;

class GenerateControllerCommand extends Command implements SelfHandling {
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
    function __construct($keyword , $conf)
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
        return $this->generateModel($this->keyword);
    }

    /**
     * Generate model
     * @param string $keyword
     * @return bool
     */
    function generateModel($keyword = "Default")
    {
        //get the model stub
        $modelStubPath = 'Stubs/Controller/'.$keyword.'.stub';
        $modelStub = $this->general->getFile($modelStubPath);

        //bind the model
        $modelStub = $this->general->quickStubDataBinding($modelStub, [
            'model'         => $this->name->getModelName($this->conf->resource),
            'view'          => $this->name->getViewFolderName($this->conf->resource),
            'route'         => $this->name->getRouteName($this->conf->resource),
            'controller'    => $this->name->getControllerName($this->conf->resource),
            'request'       => $this->name->getRequestName($this->conf->resource),
            'lang'          => $this->name->getLanguageFileName($this->conf->resource),
        ]);
        // write the file
        $modelPath = 'Http/Controllers/'. $this->name->getControllerName($this->conf->resource). '.php';
        $this->general->writeAppFile($modelPath, $modelStub);
        //give a nice good news screen
        return true;
    }
}