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

class PaypalJob {

	/**
	 * @var Payer
	 */
	private $payer;
	/**
	 * @var ItemList
	 */
	private $itemList;
	/**
	 * @var Amount
	 */
	private $amount;

	public function addItem($name, $price, $quantity) {
		$item = new Item();
		$item->setName($name)
		     ->setCurrency(env('PAYPAL_CURRENCY'))
		     ->setQuantity($quantity)
		     ->setPrice($price);
		$this->itemList->addItem($item);
	}

	public function __construct() {
		$this->handle();
	}

	public function calcAmount() {
		$this->amount = new Amount();
		$this->amount->setCurrency(env('PAYPAL_CURRENCY'));

		$total = 0;
		foreach ($this->itemList->items as $item) {
			$total += $item->price*$item->quantity;
		}
		$this->amount->setTotal($total);
	}

	public function handle() {
		$this->payer = new Payer();
		$this->payer->setPaymentMethod("paypal");
		$this->itemList = new ItemList();

	}

	/**
	 * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
	 */
	public function payment() {

		$this->calcAmount();
		$transaction = new Transaction();
		$transaction->setAmount($this->amount)
			->setItemList($this->itemList)
			->setDescription("Payment description")
			->setInvoiceNumber(uniqid());
		$baseUrl      = env('APP_URL');
		$redirectUrls = new RedirectUrls();
		$redirectUrls->setReturnUrl(url("/payment/success"))
		             ->setCancelUrl(url("/payment/fail"));
		$payment = new Payment();
		$payment->setIntent("sale")
		        ->setPayer($this->payer)
			->setRedirectUrls($redirectUrls)
			->setTransactions(array($transaction));
		//$request = clone $payment;
		$apiContext = new ApiContext(
			new OAuthTokenCredential(
				env('PAYPAL_CLIENT_ID'),
				env('PAYPAL_SECRET')
			)
		);

		$apiContext->setConfig(
			array(
				'mode'             => 'sandbox',
				'log.LogEnabled'   => true,
				'log.FileName'     => '../PayPal.log',
				'log.LogLevel'     => 'DEBUG', // PLEASE USE `FINE` LEVEL FOR LOGGING IN LIVE ENVIRONMENTS
				'validation.level' => 'log',
				'cache.enabled'    => true,
				// 'http.CURLOPT_CONNECTTIMEOUT' => 30
				// 'http.headers.PayPal-Partner-Attribution-Id' => '123123123'
			)
		);

		try {
			$payment->create($apiContext);
		} catch (Exception $ex) {
			dd($ex->getMessage());
			exit(1);
		}
		$approvalUrl = $payment->getApprovalLink();
		$token       = substr($approvalUrl, strripos($approvalUrl, "token")+6);
		session()->put('token', $token);
		return redirect($approvalUrl);
	}
}