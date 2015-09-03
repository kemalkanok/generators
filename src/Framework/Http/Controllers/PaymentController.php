<?php
/**
 * Created by PhpStorm.
 * User: kemalkanok
 * Date: 31/08/15
 * Time: 00:32
 */

namespace Kanok\Generators\Framework\Http\Controllers;


use App\Http\Controllers\Controller;
use Kanok\Generators\Libs\Payments\PaypalJob;

class PaymentController extends Controller {
    //try to make the payment
    public function index()
    {
        //paypal test
        $paypal = new PaypalJob();
        $paypal->addItem('test','7','1');
        return $paypal->payment();
    }
}