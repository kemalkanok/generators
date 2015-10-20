<?php
/**
 * Created by PhpStorm.
 * User: kemalkanok
 * Date: 31/08/15
 * Time: 00:35
 */
namespace Kanok\Generators\Libs\Payments;

use PayPal\Api\Amount;
use PayPal\Api\Item;
use PayPal\Api\ItemList;
use PayPal\Api\Payer;
use PayPal\Api\Payment;
use PayPal\Api\RedirectUrls;
use PayPal\Api\Transaction;
use PayPal\Auth\OAuthTokenCredential;
use PayPal\Rest\ApiContext;
use Sofort\SofortLib\Sofortueberweisung;

class SofortJob {


	private $provider;

	private $collection = [];

	private $total = 0;


	public function addItem($name, $price, $quantity) {
		for($i=0;$i<$quantity;$i++)
		{
			$this->collection[] = $price;
			$this->total =+ (double)$price;
		}
	}

	public function __construct() {
		$this->handle();
	}

	private function handle()
	{
		$configkey = env('SOFORT_SECRET');
		$this->provider = new Sofortueberweisung($configkey);
		$this->provider->setReason(env('SOFORT_TITLE'), date('d.m.Y'));
		$this->provider->setSuccessUrl(url('payment/success'), true);
		$this->provider->setAbortUrl(url('payment/fail'));
	}



	/**
	 * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
	 */
	public function payment() {

		$this->provider->setAmount($this->total);
		$this->provider->setCurrencyCode('EUR');
		$this->provider->sendRequest();
		if($this->provider->isError()) {
			return redirect('/payment')->withErrors([$this->provider->getError()]);
		} else {
			return redirect($this->provider->getPaymentUrl());
		}

	}
}