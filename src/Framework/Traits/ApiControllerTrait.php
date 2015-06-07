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
        return [
            'status' => 200,
            'message' => $message ? $message : "Operation success"
        ];
    }

    /**
     * @param null $message
     * @return array
     */
    public function fail($message = null)
    {
        return [
            'status' => 401,
            'message' => $message ? $message : "Operation failed"
        ];
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