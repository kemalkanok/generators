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
     * @param $model
     * @return bool
     */
    function generateModel($model)
    {
        //get the model stub
        $routesPath = 'Http/routes.php';
        $modelStub  = $this->general->getFile($routesPath);
        switch($model)
        {
            case 'auth_api':
                $modelStub = "resource('auth','User'); post('register','User@register');get('logout','User@destroy');";
            break;

            default:
                $modelStub .= "resource('".$this->name->getRouteName($this->conf->resource)."','".$this->name->getControllerName($this->conf->resource)."');";
            break;
        }
        //$modelStub .= "resource('".$this->conf->modelName."','".$this->conf->modelName."');";
        $this->general->writeAppFile($routesPath,$modelStub);
        return true;
    }
}