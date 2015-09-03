<?php
/**
 * Created by PhpStorm.
 * User: kemalkanok
 * Date: 07/06/15
 * Time: 18:44
 */

namespace Kanok\Generators\Framework\Traits;


trait ApiControllerTrait {


    /**
     * @param null $message
     * @return array
     */
    public function success($message = null)
    {
        if(!$message)
        {
            $message = "Operation success";
        }
        return [
            'status' => 200,
            'message' => $message
        ];
    }

    /**
     * @param null $message
     * @return array
     */
    public function fail($message = null)
    {
        return response()->json([
            'status' => 412,
            'message' => $message ? $message : "Operation failed"
        ],412);
    }

    /**
     * @param null $message
     * @return array
     */
    public function formFail($message = null)
    {
        return [
            'status' => 412,
            'message' => $message ? $message : "Form Failed"
        ];
    }
}