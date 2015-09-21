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
     * uploads a file with unique file name and returns the new file name
     * @param $request
     * @param $field
     * @param $path
     * @return string
     */
    public static function uniqueUpload($request,$field,$path)
    {
        if ($pic = $request->file($field)) {


            $extension = $pic->getClientOriginalExtension();
            $uniqueName = uniqid();
            $filename =  $uniqueName . "." . $extension;
            $pic->move($path, $filename);
            //create resizes pics for theme
            foreach(config('theme.sizes') as $suffix => $size)
            {
                \Image::make($path.$filename)->resize($size[0], $size[1])->save($path. $uniqueName.'.'.$suffix.'.'.$extension);
            }

            return '/'.$path . $filename;
        }
    }



}