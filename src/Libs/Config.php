<?php
/**
 * Created by PhpStorm.
 * User: kemalkanok
 * Date: 16/06/15
 * Time: 12:40
 */

namespace Kanok\Generators\Libs;


class Config {

    private $dataSet;
    /**
     * @var
     */
    private $fileHandler;

    /**
     * Construct the config data class
     */
    public function __construct()
    {

        //$this->dataSet = file_get_contents(__FILE__ . 'config.json');
        $this->fileHandler = new FileHandler();
        $this->decode();

    }

    /**
     * decodes the config file
     */
    public function decode()
    {
        $this->dataSet = $this->fileHandler->readFromFile(__DIR__ .'/../config.json');
        $this->dataSet = json_decode($this->dataSet);
    }

    /**
     * general get method for traversaling
     * @param null $key
     * @return mixed
     */
    public function get($key = null)
    {
        if($key)
        {
            return  $this->traversalConfig($key);
        }
        return $this->dataSet;

    }

    /**
     * @param $key
     * @return mixed
     */
    private function traversalConfig($key)
    {
        $key = explode('.', $key);
        $result = $this->dataSet;
        foreach ($key as $k => $v) {
            if(isset($result->$v))
            {
                $result = $result->$v;
            }
            return null;
        }
        return $result;
    }

}