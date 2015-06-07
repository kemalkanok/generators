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
    function __construct( $keyword , $conf)
    {

        $this->general = app('Kanok\Generators\Libs\FileHandler');
        $this->keyword = $keyword;
        $this->conf = $conf;
    }

    /**
     * Fire Method
     */
    function fire()
    {
        return $this->generateModel($this->keyword);
    }


    function generateModel($keyword = "Default")
    {
        //get the model stub
        $modelStubPath = 'Stubs/Controller/'.$keyword.'.stub';
        $modelStub = $this->general->getFile($modelStubPath);
        //bind the model


        $modelStub = $this->general->quickStubDataBinding($modelStub, [
            'model' => $this->conf->modelName
        ]);
        // write the file
        $modelPath = 'Http/Controllers/'. $this->conf->modelName . '.php';
        $this->general->writeAppFile($modelPath, $modelStub);
        //give a nice good news screen
        return true;
    }
}