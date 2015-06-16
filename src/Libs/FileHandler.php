<?php
namespace Kanok\Generators\Libs;

use Illuminate\Filesystem\Filesystem;
use Illuminate\Foundation\Composer;

class FileHandler {
    /**
     * @var Filesystem
     */
    private $filesystem;
    /**
     * @var Composer
     */
    private $composer;

    /**
     * General Constructor method
     * @param Filesystem $filesystem
     * @param Composer $composer
     */
    public  function __construct(Filesystem $filesystem , Composer $composer)
    {
        $this->filesystem = $filesystem;
        $this->composer = $composer;
    }

    /**
     * Gets the requested file
     * @param $filename
     * @return string
     */
    public function getFile($filename)
    {
        $file = app_path($filename);
        if(! $this->filesystem->exists($file)) {
            $file = __DIR__ .  DIRECTORY_SEPARATOR . '..' .DIRECTORY_SEPARATOR . $filename;
        }
        return $this->filesystem->get($file);
    }

    /**
     * Writes the requested text to the file
     * @param $filename
     * @param $text
     * @return int
     */
    public function writeFile($filename , $text , $location = null)
    {
        $file = app_path($filename);
        if($location)
        {
            $file = $location.$filename;
        }
        $this->filesystem->put($file,$text);
        return true;
    }


    /**
     * Binds the data via replacement to Stub
     * @param $stub
     * @param array $arr
     * @return mixed
     */
    public function stubDataBinding($stub , array $arr )
    {
        foreach($arr as $key => $value)
        {
            $stub = str_replace('{{'.$key.'}}',$value,$stub);
        }
        return $stub;
    }

    /**
     * Creates folder if not exists
     *
     * @param $path
     */
    public function createFolder($path)
    {
        $path = app_path($path);
        if(!$this->filesystem->exists($path))
        {
            $this->filesystem->makeDirectory($path,0777,true);
        }
    }
}