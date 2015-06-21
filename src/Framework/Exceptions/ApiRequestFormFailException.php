<?php
/**
 * Created by PhpStorm.
 * User: kemalkanok
 * Date: 07/06/15
 * Time: 18:18
 */

namespace Kanok\Generators\Framework\Exceptions;


class ApiRequestFormFailException extends \Exception {
    public function __construct($message)
    {
        $this->message = $message;
    }
}