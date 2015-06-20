<?php
namespace Kanok\Generators\Libs;

class FileHandler {

    /** 
     * reads a file from one of bundle folders 
     * 
     * @return string
     */
    function readFileFromBundle($file,$basePath = null)
    {
        $basePath = $this->inheritSettings($basePath,'stubPath',true);
        return file_get_contents($basePath.$file);
    }

    /**
     * reads a file from one of  app folders 
     * 
     * @return string
     */ 
    function readFileFromApp($file,$basePath = null)
    {
        $basePath = $this->inheritSettings($basePath,'appPath');
        return file_get_contents($basePath.$file);
    }

    /**
     * Writes a file inside of an app files
     * 
     * @return void
     */ 
    function writeFileToApp($content , $path,$file)
    {
        $basePath = $this->inheritSettings($path);
        if(is_dir($basePath))
        {
            $this->writeOut($basePath.$file , $content);
        }
        else
        {
                echo "@todo:will create folder first than call writeOut method!!!";
        }
        //echo $basePath;
    }

    /**
     * Writes the string to  the specific file
     * 
     * @return void
     */ 
    private function writeOut($file,$content)
    {
        $fh = fopen($file, 'w');
        fwrite($fh, $content);
        fclose($fh);
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
                $basePath = app_path((new Config())->get($key));
            }
            return $basePath;
        }
        return  app_path( '../' .$basePath);
    }

}