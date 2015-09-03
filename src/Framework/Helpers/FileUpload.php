<?php
/**
 * Created by PhpStorm.
 * User: kemalkanok
 * Date: 09/06/15
 * Time: 11:26
 */

namespace Kanok\Generators\Framework\Helpers;



class FileUpload {

    /**
     * uploads a file with unique user name and returns the new file name
     * @param $request
     * @param $field
     * @param $path
     * @return string
     */
    public static function uniqueUpload($request,$field,$path)
    {
        if ($pic = $request->file($field)) {
            $extension = $pic->getClientOriginalExtension();
            $filename = uniqid() . "." . $extension;
            $pic->move($path, $filename);
            return '/'.$path . $filename;
        }
    }



}