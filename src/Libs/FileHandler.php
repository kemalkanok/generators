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
        $basePath = $this->inheritSettings($basePath,'stubPath');
        return file_get_contents($basePath.$file);
    }
    function readFileFromApp($file,$basePath = null)
    {
        $basePath = $this->inheritSettings($basePath,'appPath');
        return file_get_contents($basePath.$file);
    }

    /**
     * @param $basePath
     * @return string
     */
    private function inheritSettings($basePath,$key)
    {
        if (!$basePath) {
            $basePath = __DIR__ . '/' . (new Config())->get($key);
            return $basePath;
        }
        return $basePath;
    }

}