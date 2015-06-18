<?php
namespace Kanok\Generators\Libs;



class FileHandler {

    /**
     * reads from a file
     * @param $file
     * @param null $basePath
     * @return string
     */
    function readFileFromBundle($file,$basePath = null)
    {
        if(!$basePath)
        {
            $basePath = __DIR__ .'/'. (new Config())->get('stubPath');
        }
        return file_get_contents($basePath.$file);
    }
    function readFileFromApp($file,$basePath = null)
    {
        if(!$basePath)
        {
            $basePath = app_path() .'/../'. (new Config())->get('basePath');
        }
        return file_get_contents($basePath.$file);
    }

}