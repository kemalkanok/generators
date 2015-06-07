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

class GenerateCrudCommand extends Command implements SelfHandling {
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
        $this->generateIndex($keyword);
        $this->generateCreate($keyword);
        $this->generateEdit($keyword);
        return true;
    }
    function generateIndex($keyword)
    {
        //get the model stub
        $modelStubPath = 'Stubs/View/Index/'.$keyword.'.stub';
        $modelStub = $this->general->getFile($modelStubPath);
        //bind the model

        $modelStub = $this->general->quickStubDataBinding($modelStub, [
            'model' => $this->conf->modelName
        ]);
        // write the file
        $indexFolder ='../resources/views/'. $this->conf->modelName . "/";
        $this->general->createFolder($indexFolder);

        $modelPath = '../resources/views/'. $this->conf->modelName . '/index.blade.php';
        $this->general->writeAppFile($modelPath, $modelStub);
        //give a nice good news screen
    }

    function generateCreate($keyword)
    {
        //get the model stub
        $modelStubPath = 'Stubs/View/Create/' . $keyword . '.stub';
        $modelStub = $this->general->getFile($modelStubPath);
        //bind the model

        $fillables = "";
        foreach ($this->conf->fields as $key => $value) {

            $fillables .= '<div class="form-group">
        <label for="'.$key.'">{{trans("'.$this->conf->modelName.'.'.$key.'")}}</label>
        <input type="text" class="form-control" id="'.$key.'"  value="{{old("'.$key.'")}}" name="'.$key.'">
    </div>
    ';




        }
        $modelStub = $this->general->quickStubDataBinding($modelStub, [
            'model' => $this->conf->modelName,
            'form' => $fillables
        ]);
        // write the file
        $indexFolder ='../resources/views/'. $this->conf->modelName . "/";
        $this->general->createFolder($indexFolder);

        $modelPath = '../resources/views/'. $this->conf->modelName . '/create.blade.php';
        $this->general->writeAppFile($modelPath, $modelStub);
        //give a nice good news screen
    }

    function generateEdit($keyword)
    {
        //get the model stub
        $modelStubPath = 'Stubs/View/Edit/' . $keyword . '.stub';
        $modelStub = $this->general->getFile($modelStubPath);
        //bind the model

        $fillables = "";
        foreach ($this->conf->fields as $key => $value) {

            $fillables .= '<div class="form-group">
        <label for="'.$key.'">{{trans("'.$this->conf->modelName.'.'.$key.'")}}</label>
        <input type="text" class="form-control" id="'.$key.'"  name="'.$key.'" value="{{$element->'.$key.'}}">
    </div>
    ';
        }



        $modelStub = $this->general->quickStubDataBinding($modelStub, [
            'model' => $this->conf->modelName,
            'form' => $fillables
        ]);
        // write the file
        $indexFolder ='../resources/views/'. $this->conf->modelName . "/";
        $this->general->createFolder($indexFolder);

        $modelPath = '../resources/views/'. $this->conf->modelName . '/edit.blade.php';
        $this->general->writeAppFile($modelPath, $modelStub);
        //give a nice good news screen
    }
}