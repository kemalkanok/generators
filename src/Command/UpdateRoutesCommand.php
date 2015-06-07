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

class UpdateRoutesCommand extends Command implements SelfHandling {
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
        return $this->generateModel();
    }


    function generateModel()
    {
        //get the model stub
        $routesPath = 'Http/Routes.php';
        $modelStub = $this->general->getFile($routesPath);
        $modelStub .= "
        resource('".$this->conf->modelName."','".$this->conf->modelName."');";
        $this->general->writeAppFile($routesPath,$modelStub);
        return true;
    }
}