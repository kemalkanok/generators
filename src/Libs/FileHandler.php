<?php
namespace Kanok\Generators\Libs;



class FileHandler {

    function readFromFile($file)
    {
        return file_get_contents($file);
    }

}