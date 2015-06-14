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
        $this->general  = app('Kanok\Generators\Libs\FileHandler');
        $this->name     = app('Kanok\Generators\Libs\NameHelper');
        $this->keyword  = $keyword;
        $this->conf     = $conf;
        $this->common   = [
            'model'     => $this->name->getModelName($this->conf->resource),
            'route'     => $this->name->getRouteName($this->conf->resource),
            'lang'      => $this->name->getLanguageFileName($this->conf->resource),
        ];
    }

    /**
     * Fire Method
     */
    function fire()
    {
        return $this->generateModel($this->keyword);
    }

    /**
     * Generate views
     * @param string $keyword
     * @return bool
     */
    function generateModel($keyword = "Default")
    {
        //api check
        if($keyword == 'api')
            return true;
        $this->generateIndex($keyword);
        $this->generateCreate($keyword);
        $this->generateEdit($keyword);
        $this->generateShow($keyword);

        return true;
    }

    /**
     * Generate index page
     * @param $keyword
     */
    function generateIndex($keyword)
    {
        //get the model stub
        $modelStubPath = 'Stubs/View/Index/'.$keyword.'.stub';
        $modelStub = $this->general->getFile($modelStubPath);
        //bind the model

        $modelStub = $this->general->quickStubDataBinding($modelStub,$this->common);
        // write the file
        $indexFolder ='../resources/views/'. $this->name->getViewFolderName($this->conf->resource) . "/";
        $this->general->createFolder($indexFolder);

        $modelPath = '../resources/views/'. $this->name->getViewFolderName($this->conf->resource) . '/index.blade.php';
        $this->general->writeAppFile($modelPath, $modelStub);
        //give a nice good news screen
    }

    /**
     * Generate create page
     * @param $keyword
     */
    function generateCreate($keyword)
    {
        //get the model stub
        $modelStubPath = 'Stubs/View/Create/' . $keyword . '.stub';
        $modelStub = $this->general->getFile($modelStubPath);
        //bind the model

        $fillables = "";
        foreach ($this->conf->fields as $key => $value) {
            $fillables .= '<div class="form-group">
                    <label for="'.$key.'">{{ trans("validation.attributes.'.$key.'") }}</label>
                    <input type="text" class="form-control" id="'.$key.'"  value="{{ old("'.$key.'") }}" name="'.$key.'">
                </div>';
        }
        $modelStub = $this->general->quickStubDataBinding($modelStub, array_add($this->common,'form', $fillables) );
        // write the file
        $indexFolder ='../resources/views/'. $this->name->getViewFolderName($this->conf->resource) . "/";
        $this->general->createFolder($indexFolder);

        $modelPath = '../resources/views/'. $this->name->getViewFolderName($this->conf->resource) . '/create.blade.php';
        $this->general->writeAppFile($modelPath, $modelStub);
        //give a nice good news screen
    }

    /**
     * Generate edit page
     * @param $keyword
     */
    function generateEdit($keyword)
    {
        //get the model stub
        $modelStubPath  = 'Stubs/View/Edit/' . $keyword . '.stub';
        $modelStub      = $this->general->getFile($modelStubPath);

        //bind the model
        $fillables      = "";
        foreach ($this->conf->fields as $key => $value) {
            $fillables .= '<div class="form-group">
                    <label for="'.$key.'">{{ trans("validation.attributes.'.$key.'") }}</label>
                    <input type="text" class="form-control" id="'.$key.'"  name="'.$key.'" value="{{ $element->'.$key.' }}">
                </div>';
        }
        $modelStub = $this->general->quickStubDataBinding($modelStub, array_add($this->common,'form', $fillables));
        // write the file
        $indexFolder ='../resources/views/'. $this->name->getViewFolderName($this->conf->resource) . "/";
        $this->general->createFolder($indexFolder);

        $modelPath = '../resources/views/'. $this->name->getViewFolderName($this->conf->resource) . '/edit.blade.php';
        $this->general->writeAppFile($modelPath, $modelStub);
        //give a nice good news screen
    }

    /**
     * Generate show page
     * @param $keyword
     */
    function generateShow($keyword)
    {
        //get the model stub
        $modelStubPath  = 'Stubs/View/Show/' . $keyword . '.stub';
        $modelStub      = $this->general->getFile($modelStubPath);
        //bind the model
        $content        = "";
        foreach ($this->conf->fields as $key => $value) {
            $content .= '<dt>{{ trans("validation.attributes.'.$key.'") }}</dt>
                        <dd>{{ $element->'.$key.' }}</dd>';
        }
        $modelStub = $this->general->quickStubDataBinding($modelStub, array_add($this->common,'content', $content) );
        // write the file
        $indexFolder ='../resources/views/'. $this->name->getViewFolderName($this->conf->resource) . "/";
        $this->general->createFolder($indexFolder);

        $modelPath = '../resources/views/'. $this->name->getViewFolderName($this->conf->resource) . '/show.blade.php';
        $this->general->writeAppFile($modelPath, $modelStub);
        //give a nice good news screen
    }
}