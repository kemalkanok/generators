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
        $basePath = $this->inheritSettings($basePath,'stubPath',true);
        return file_get_contents($basePath.$file);
    }
    function readFileFromApp($file,$basePath = null)
    {
        $basePath = $this->inheritSettings($basePath,'appPath');
        return file_get_contents($basePath.$file);
    }

    function writeFileToApp($content , $path)
    {
        $basePath = $this->inheritSettings($path).$path;
        echo $basePath;
    }

    /**
     * Gets the specific package configurations
     * @param $basePath
     * @return string
     */
    private function inheritSettings($basePath,$key = 'appPath' , $package = false)
    {
        if (!$basePath) {
            if($package)
            {
                $basePath = __DIR__ . '/' . (new Config())->get($key);
            }
            else
            {
                $basePath = app_path() . '../' . (new Config())->get($key);
            }
            return $basePath;
        }
        return  app_path() . '../' .$basePath;
    }

    
}